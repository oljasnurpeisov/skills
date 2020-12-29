<?php

namespace App\Api\V1\Controllers\App;

use App\Api\V1\Classes\Message;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Transformers\MessageTransformer;
use App\Extensions\FormatDate;
use App\Extensions\NotificationsHelper;
use App\Models\Dialog;
use App\Models\Notification;
use App\Models\Role;
use App\Models\Skill;
use App\Models\StudentCourse;
use App\Models\StudentInformation;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    public function studentLogin(Request $request)
    {
        $login = $request->get('login');
        $password = $request->get('password');
        $hash = $request->header("hash");
        $lang = $request->header("lang", 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'login' => 'required',
            'password' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'login' => $login,
            'password' => $password,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $client = new Client(['verify' => false]);

        try {
            $body = array("login" => $login, "password" => $password);
            $response = $client->request('POST', 'https://btest.enbek.kz/ru/api/auth/login', [
                'body' => json_encode($body),
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);
        } catch (BadResponseException $e) {
            $errors = $validator->errors()->all();
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $token = json_decode($response->getBody(), true);
        $token = $token["response"]["token"];
        $student_resume = json_decode($this->getStudentResume($token), true);
        $student_unemployed_status = json_decode($this->getUnemployedStatus($token), true);

        $user = User::where('email', '=', $login)->first();

        $student_role = Role::where('slug', '=', 'student')->first();

        if (empty($user)) {

            $item = new User;
            $item->email = $login;
            $item->is_activate = 1;
            $item->email_verified_at = Carbon::now()->toDateTimeString();
            $item->save();

            $item->roles()->sync([$student_role->id]);

            $item_information = new StudentInformation;
            $item_information->user_id = $item->id;

            if ($student_resume != null) {
                $item_information->name = $student_resume[0]["FIO"];
                $item_information->uid = $student_resume[0]["uid"];
                $item_information->profession_code = $student_resume[0]["uozcodprof"];

                $user_skills = array();
                foreach ($student_resume[0]["compSpecList"] as $skill) {
                    array_push($user_skills, $skill["codcomp"]);
                }
                $skills = Skill::whereIn('code_skill', $user_skills)->pluck('id')->toArray();
                $item->skills()->sync($skills);
            }

            if ($student_unemployed_status["response"] == null) {
                $item_information->unemployed_status = 0;
            } else {
                $item_information->unemployed_status = 1;
                $item_information->quota_count = 3;
            }
            $item_information->save();

            $this->createTechDialog($item->id);

            $data = [
                'id' => $item->id,
                'email' => $item->email,
                'name' => $item->student_info->name,
                'quota_count' => $item->student_info->quota_count,
            ];

        } else {

            if ($user->roles()->first()->id != $student_role->id) {
                return $this->response->item(__('default.pages.auth.student_login_author_exist'), new MessageTransformer())->statusCode(400);
            }

            $data = [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->student_info->name,
                'quota_count' => $user->student_info->quota_count,
            ];
        }

        $message = new Message(__('api/messages.success'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function saveStudentResumeData(Request $request)
    {
        $user_id = $request->get("user");
        $name = $request->get("resume_name");
        $iin = $request->get("resume_iin");
        $hash = $request->header("hash");
        $lang = $request->header("lang", 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'user' => 'required',
            'resume_iin' => 'required|unique:student_information,iin|numeric|digits:12',
            'resume_name' => 'required|max:255',
            'hash' => 'required',
        ];
        $payload = [
            'resume_iin' => $iin,
            'resume_name' => $name,
            'user' => $user_id,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $user = User::whereId($user_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        StudentInformation::whereUserId($user_id)->update([
            'name' => $name,
            'iin' => $iin,
        ]);

        $data = [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->student_info->name,
            'quota_count' => $user->student_info->quota_count,
        ];

        $message = new Message(__('api/messages.success'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function uploadAvatar(Request $request)
    {

        $avatar = $request->file('file');
        $user_id = $request->header('user');
        $hash = $request->header('hash');
        $lang = $request->header('lang', 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'user' => 'required',
            'hash' => 'required',
            'file' => ['max:1024'],
        ];
        $payload = [
            'file' => $avatar,
            'user' => $user_id,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        unset($payload["file"]);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $item = StudentInformation::where('user_id', '=', $user_id)->first();

        if (!$item) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        File::delete(public_path($item->avatar));

        $file = $request->file("file");

        if ($request->has('file')) {
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('users/user_' . $user_id . '/profile/images'), $imageName);

            $item->avatar = '/users/user_' . $user_id . '/profile/images/' . $imageName;
        } else {
            $item->avatar = null;
        }

        $item->save();

        $message = new Message(__('api/messages.success'), 200, null);
        return $this->response->item($message, new MessageTransformer());
    }

    public function getNotifications(Request $request)
    {
        $user_id = $request->get('user');
        $hash = $request->header('hash');
        $lang = $request->header('lang', 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'user' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'user' => $user_id,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $user = User::whereId($user_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        $items = $user->notifications()->paginate($this->per_page);

        $next_page_number = null;
        if ($items->nextPageUrl()) {
            $t = parse_url($items->nextPageUrl());
            $t = isset($t["query"]) ? $t["query"] : "";
            $t = explode("=", $t);
            $next_page_number = in_array("page", $t) ? $t[array_search("page", $t) + 1] : null;
        }

        $data = [
            "items" => [],
            "next" => $next_page_number,
        ];

        foreach ($items as $item) {
            $data["items"][] = [
                'id' => $item->id,
                'text' => strip_tags(trans($item->name, ['course_name' => '"' . optional($item->course)->name . '"', 'lang' => $lang, 'course_id' => optional($item->course)->id, 'opponent_id' => json_decode($item->data)[0]->dialog_opponent_id ?? 0, 'reject_message' => json_decode($item->data)[0]->course_reject_message ?? ''])),
                'date' => $item->created_at
            ];
        }

        $message = new Message(__('api/messages.notifications.title'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function getDialogs(Request $request)
    {
        $user_id = $request->get('user');
        $hash = $request->header("hash");
        $lang = $request->header("lang", 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'user' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'user' => $user_id,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $user = User::whereId($user_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        $items = Dialog::whereHas("members", function ($q) use ($user) {
            $q->where("user_id", "=", $user->id);
        })->with('members')->orderBy("updated_at", "desc")->paginate($this->per_page);

        $next_page_number = null;
        if ($items->nextPageUrl()) {
            $t = parse_url($items->nextPageUrl());
            $t = isset($t["query"]) ? $t["query"] : "";
            $t = explode("=", $t);
            $next_page_number = in_array("page", $t) ? $t[array_search("page", $t) + 1] : null;
        }

        $data = [
            "items" => [],
            "next" => $next_page_number,
        ];

        foreach ($items as $item) {
            $member = $item->members->where('id', '!=', $user->id)->first();

            if ($member->hasRole('author')) {
                $member_id = $member->id;
                $member_name = $member->author_info->name . ' ' . $member->author_info->surname;
                $member_avatar = $member->author_info->getAvatar();
            } else if ($member->hasRole('student')) {
                $member_id = $member->id;
                $member_name = $member->student_info->name ?? __('default.pages.profile.student_title');
                $member_avatar = $member->student_info->getAvatar();
            } else {
                $member_id = $member->id;
                $member_name = $member->name;
                $member_avatar = null;
            }
            $data["items"][] = [
                'id' => $item->id,
                'opponent_id' => $member_id,
                'image' => $member_avatar,
                'opponent_name' => $member_name,
                'text' => json_decode('"' . str_replace('"', '\"', $item->lastMessageText()) . '"'),
                'date' => $item->lastMessageDate()
            ];
        }

        $message = new Message(__('api/messages.dialogs.title'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function getDialog(Request $request)
    {
        $user_id = $request->get('user');
        $opponent_id = $request->get('opponent');
        $hash = $request->header("hash");
        $lang = $request->header("lang", 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'user' => 'required',
            'opponent' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'opponent' => $opponent_id,
            'user' => $user_id,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $user = User::whereId($user_id)->first();
        $opponent = User::whereId($opponent_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        if (!$opponent) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        $item = $user->dialogs()->whereHas("members", function ($q) use ($opponent) {
            $q->where("user_id", "=", $opponent->id);
        })->first();

        if (!$item) {

            if ($user->hasRole('student')) {
                $author_course = StudentCourse::where('student_id', '=', $user->id)
                    ->whereHas('course', function ($q) use ($opponent_id) {
                        $q->where('author_id', '=', $opponent_id);
                    })->get();
                if (count($author_course) != 0) {
                    $item = new Dialog();
                    $item->save();

                    $item->members()->attach($user->id);
                    $item->members()->attach($opponent_id);
                } else {
                    $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
                    return $this->response->item($message, new MessageTransformer())->statusCode(404);
                }
            }
        }

        $items = $item->messages()->orderBy('created_at', 'desc')->paginate($this->per_page);

        $next_page_number = null;
        if ($items->nextPageUrl()) {
            $t = parse_url($items->nextPageUrl());
            $t = isset($t["query"]) ? $t["query"] : "";
            $t = explode("=", $t);
            $next_page_number = in_array("page", $t) ? $t[array_search("page", $t) + 1] : null;
        }

        $data = [
            "items" => [],
            "next" => $next_page_number,
        ];

        foreach ($items as $item) {
            $sender = $item->sender;

            if ($sender->hasRole('author')) {
                $sender_id = $sender->id;
                $sender_name = $sender->author_info->name . ' ' . $sender->author_info->surname;
                $sender_avatar = $sender->author_info->getAvatar();
            } else if ($sender->hasRole('student')) {
                $sender_id = $sender->id;
                $sender_name = $sender->student_info->name ?? __('default.pages.profile.student_title');
                $sender_avatar = $sender->student_info->getAvatar();
            } else {
                $sender_id = $sender->id;
                $sender_name = $sender->name;
                $sender_avatar = null;
            }
            $data["items"][] = [
                'id' => $item->id,
                'image' => $sender_avatar,
                'sender_name' => $sender_name,
                'text' => $item->message,
                'date' => $item->created_at
            ];
        }

        $message = new Message(__('api/messages.dialogs.title'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function getCertificates(Request $request)
    {
        $user_id = $request->get('user');
        $hash = $request->header('hash');
        $lang = $request->header('lang', 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'user' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'user' => $user_id,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $user = User::whereId($user_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        $items = $user->certificates()->paginate($this->per_page);

        $next_page_number = null;
        if ($items->nextPageUrl()) {
            $t = parse_url($items->nextPageUrl());
            $t = isset($t["query"]) ? $t["query"] : "";
            $t = explode("=", $t);
            $next_page_number = in_array("page", $t) ? $t[array_search("page", $t) + 1] : null;
        }

        $data = [
            "items" => [],
            "next" => $next_page_number,
        ];

        foreach ($items as $item) {
            $data["items"][] = [
                'id' => $item->id,
                'image' => $item->png_ru,
                'pdf' => $item->pdf_ru
            ];
            $data["items"][] = [
                'id' => $item->id,
                'image' => $item->png_kk,
                'pdf' => $item->pdf_kk
            ];
        }

        $message = new Message(__('api/messages.certificates.title'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function saveMessage(Request $request)
    {
        $user_id = $request->get('user');
        $dialog_id = $request->get('dialog');
        $message = $request->get('message');
        $hash = $request->header('hash');
        $lang = $request->header('lang', 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'dialog' => 'required',
            'message' => 'required',
            'user' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'dialog' => $dialog_id,
            'message' => $message,
            'user' => $user_id,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $user = User::whereId($user_id)->first();
        $dialog = Dialog::whereId($dialog_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }
        if (!$dialog) {
            $message = new Message(Lang::get("api/errors.dialog_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        $item = new \App\Models\Message;
        $item->dialog_id = $dialog->id;
        $item->sender_id = $user->id;
        $item->message = $message;
        $item->save();

        $dialog->updated_at = Carbon::now();
        $dialog->save();

        $opponent = $dialog->members()->where('user_id', '!=', $user->id)->first();

        $notification_data = [
            'dialog_opponent_id' => $user->id
        ];
        $notification_name = 'notifications.new_message';
        NotificationsHelper::createNotification($notification_name, null, $opponent->id, 0, $notification_data);

        $message = new Message(__('api/messages.success'), 200, null);
        return $this->response->item($message, new MessageTransformer());
    }

    public function updateToken(Request $request)
    {
        $user_id = $request->get('user');
        $ios_token = $request->get('ios_token');
        $android_token = $request->get('android_token');
        $hash = $request->header('hash');
        $lang = $request->header('lang', 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'user' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'user' => $user_id,
            'ios_token' => $ios_token,
            'android_token' => $android_token,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $user = User::whereId($user_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        $user->ios_token = $ios_token;
        $user->android_token = $android_token;
        $user->save();

        $message = new Message(__('api/messages.success'), 200, null);
        return $this->response->item($message, new MessageTransformer());
    }

    public function updatePushStatus(Request $request)
    {
        $user_id = $request->get('user');
        $status = $request->get('status');
        $hash = $request->header('hash');
        $lang = $request->header('lang', 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'user' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'user' => $user_id,
            'status' => $status,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $user = User::whereId($user_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        $user->is_push_activate = $status;
        $user->save();

        $message = new Message(__('api/messages.success'), 200, null);
        return $this->response->item($message, new MessageTransformer());
    }

    public function getStudentResume($token)
    {
        $client = new Client(['verify' => false]);

        try {
            $response = $client->request('GET', 'https://btest.enbek.kz/ru/api/resume-for-obuch', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'token' => $token
                ]
            ]);
        } catch (BadResponseException $e) {
            return 404;
        }

        return $response->getBody();
    }

    public function getUnemployedStatus($token)
    {
        $client = new Client(['verify' => false]);

        try {
            $response = $client->request('GET', 'http://btest.enbek.kz/ru/api/bezrab-for-obuch', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'token' => $token
                ]
            ]);
        } catch (BadResponseException $e) {
            return 404;
        }

        return $response->getBody();
    }

    public function createTechDialog($user_id)
    {
        // Создание диалога с тех.поддержкой
        $tech_support = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'tech_support');
        })->first();

        $dialog = new Dialog;
        $dialog->save();

        $dialog->members()->sync([$user_id, $tech_support->id]);
    }
}
