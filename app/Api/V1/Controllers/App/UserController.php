<?php

namespace App\Api\V1\Controllers\App;

use App\Api\V1\Classes\Message;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Transformers\MessageTransformer;
use App\Extensions\FormatDate;
use App\Extensions\NotificationsHelper;
use App\Models\Dialog;
use App\Models\Notification;
use App\Models\Professions;
use App\Models\Role;
use App\Models\Skill;
use App\Models\StudentCourse;
use App\Models\StudentInformation;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    public function studentInfo(Request $request)
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

        $data = [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->student_info->name,
            'avatar' => env('APP_URL') . $user->student_info->avatar,
            'is_push_activate' => $user->is_push_activate,
            'iin' => $user->student_info->iin,
            'quota_count' => $user->student_info->quota_count,
        ];

        $message = new Message(__('api/messages.success'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

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
            $message = new Message(Lang::get("api/errors.request_failed"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        } catch (GuzzleException $b) {
            $message = new Message(Lang::get("api/errors.request_failed"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $authData = json_decode($response->getBody(), true);
        $token = $authData["response"]["token"];
        $uid = $authData["response"]["uid"];

        $studentUnemployedStatus = json_decode($this->getUnemployedStatus($token), true);
        $studentResumes = json_decode($this->getStudentResume($token), true);

        $user = User::whereEmail($login)->first();
        $student_role = Role::whereSlug('student')->first();

        if (empty($user)) {
            $user = new User;
            $user->email = $request->email;
            $user->is_activate = 1;
            $user->email_verified_at = Carbon::now()->toDateTimeString();
            $user->save();

            $user->roles()->sync([$student_role->id]);

            $this->createTechDialog($user->id);
        }

        $studentInformation = StudentInformation::whereUserId($user->id)->first();
        if (empty($studentInformation)) {
            $studentInformation = new StudentInformation();
            $studentInformation->user_id = $user->id;
            $studentInformation->uid = $uid;

            if ($studentUnemployedStatus["response"] == null) {
                $studentInformation->unemployed_status = 0;
            } else {
                $studentInformation->unemployed_status = 1;
                $studentInformation->quota_count = 3;
            }

            if (($studentResumes != null) && ($studentResumes != [])) {
                $setFullNameAndIIN = true;
                $userSkills = array();
                $userProfessions = array();

                foreach ($studentResumes as $studentResume) {
                    if ($setFullNameAndIIN) {
                        $studentInformation->name = $studentResume["FIO"];
                        $studentInformation->iin = $studentResume["iin"];
                    }

                    foreach ($studentResume["compSpecList"] as $skill) {
                        array_push($userSkills, $skill["codcomp"]);
                    }

                    array_push($userSkills, $studentResume["uozcodprof"]);
                }

                $professions = Professions::whereIn('code', $userProfessions)->pluck('id')->toArray();
                $user->professions()->sync($professions);

                $skills = Skill::whereIn('code_skill', $userSkills)->pluck('id')->toArray();
                $user->skills()->sync($skills);
            }

            $studentInformation->save();
        }

        if ($studentInformation->name == null || $studentInformation->iin == null) {
            if (($studentResumes != null) && ($studentResumes != [])) {
                $studentResume = $studentResumes[0];
                $studentInformation->name = $studentResume["FIO"];
                $studentInformation->iin = $studentResume["iin"];
                $studentInformation->save();
            }
        }

        if ($user->roles()->first()->id != $student_role->id) {
            return redirect()->back()->with('status', __('default.pages.auth.student_login_author_exist'));
        }

        $data = [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->student_info->name,
            'avatar' => env('APP_URL') . $user->student_info->avatar,
            'is_push_activate' => $user->is_push_activate,
            'iin' => $user->student_info->iin,
            'quota_count' => $user->student_info->quota_count,
        ];

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

        $items = $user->notifications()->orderBy('created_at', 'desc')->paginate($this->per_page);

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

        /** @var Notification $item */
        foreach ($items as $item) {
            $opponent = User::whereId(json_decode($item->data)[0]->dialog_opponent_id ?? 0)->first();
            $userName = '';
            if ($opponent) {
                if ($opponent->hasRole('author')) {
                    $userName = $opponent->author_info->name . ' ' . $opponent->author_info->surname;
                } else {
                    $userName = $opponent->student_info->name ?? $opponent->name;
                }
            }

            switch ($item->name) {
                case 'notifications.new_message':
                    $link = [
                        'type' => $item->name,
                        'id' => json_decode($item->data)[0]->dialog_opponent_id ?? null,
                        'name' => $userName,
                    ];
                    break;
                case 'notifications.course_student_finished':
                case 'notifications.course_buy_status_success':
                    $link = [
                        'type' => $item->name,
                        'id' => optional($item->course)->id,
                        'name' => optional($item->course)->name
                    ];
                    break;
                default:
                    $link = null;
                    break;
            }

            $data["items"][] = [
                'id' => $item->id,
                'text' => strip_tags(trans($item->name, [
                    'course_name' => '"' . optional($item->course)->name . '"',
                    'lang' => $lang,
                    'course_id' => optional($item->course)->id,
                    'opponent_id' => json_decode($item->data)[0]->dialog_opponent_id ?? 0,
                    'reject_message' => json_decode($item->data)[0]->course_reject_message ?? '',
                    'user_name' => $userName
                ])),
                'link' => $link,
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

        $items = Dialog::whereIsTs(0)
            ->whereHas("members", function ($q) use ($user) {
                $q->where("user_id", "=", $user->id);
            })
            ->orderBy("updated_at", "desc")
            ->paginate($this->per_page);

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

        $curPage = $request->get('page', null);
        if ($curPage == null || $curPage == 1) {
            $d = Dialog::whereIsTs(1)
                ->whereHas("members", function ($q) use ($user) {
                    $q->where("user_id", "=", $user->id);
                })->first();

            if ($d) {
                /** @var User $member */
                $member = $d->members->where('id', '!=', $user->id)->first();

                $member_id = $member->id;
                $member_name = $member->name;
                $member_avatar = env('APP_URL') . '/assets/img/tech_support_avatar.png';

                $data["items"][] = [
                    'id' => $d->id,
                    'opponent_id' => $member_id,
                    'image' => $member_avatar,
                    'opponent_name' => $member_name,
                    'text' => json_decode('"' . str_replace('"', '\"', $d->lastMessageText()) . '"'),
                    'date' => $d->lastMessageDate()
                ];
            }

        }

        foreach ($items as $item) {
            $member = $item->members->where('id', '!=', $user->id)->first();

            if ($member->hasRole('author')) {
                $member_id = $member->id;
                $member_name = $member->author_info->name . ' ' . $member->author_info->surname;
                $member_avatar = env('APP_URL') . $member->author_info->getAvatar();
            } else if ($member->hasRole('student')) {
                $member_id = $member->id;
                $member_name = $member->student_info->name ?? __('default.pages.profile.student_title');
                $member_avatar = env('APP_URL') . $member->student_info->getAvatar();
            } else if ($member->hasRole('tech_support')) {
                $member_id = $member->id;
                $member_name = $member->name;
                $member_avatar = env('APP_URL') . '/assets/img/tech_support_avatar.png';
            } else {
                $member_id = $member->id;
                $member_name = $member->name;
                $member_avatar = null;
            }

            if ($member->hasRole('tech_support')) {
                array_unshift($data["items"], [
                    'id' => $item->id,
                    'opponent_id' => $member_id,
                    'image' => $member_avatar,
                    'opponent_name' => $member_name,
                    'text' => json_decode('"' . str_replace('"', '\"', $item->lastMessageText()) . '"'),
                    'date' => $item->lastMessageDate()
                ]);
            } else {
                $data["items"][] = [
                    'id' => $item->id,
                    'opponent_id' => $member_id,
                    'image' => $member_avatar,
                    'opponent_name' => $member_name,
                    'text' => json_decode('"' . str_replace('"', '\"', $item->lastMessageText()) . '"'),
                    'date' => $item->lastMessageDate()
                ];
            }
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
                $sender_avatar = env('APP_URL') . $sender->author_info->getAvatar();
            } else if ($sender->hasRole('student')) {
                $sender_id = $sender->id;
                $sender_name = $sender->student_info->name ?? __('default.pages.profile.student_title');
                $sender_avatar = env('APP_URL') . $sender->student_info->getAvatar();
            } else {
                $sender_id = $sender->id;
                $sender_name = $sender->name;
                $sender_avatar = null;
            }
            $data["items"][] = [
                'id' => $item->id,
                'image' => $sender_avatar,
                'sender_name' => $sender_name,
                'sender_id' => $sender_id,
                'text' => $item->message,
                'date' => $item->created_at
            ];
        }

        $message = new Message(__('api/messages.dialogs.title'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function getDialogByOpponent(Request $request)
    {
        $user_id = $request->get('user');
        $opponent_id = $request->get('opponent');
        $hash = $request->header('hash');
        $lang = $request->header('lang', 'ru');
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

        $dialog = Dialog::whereHas('members', function ($q) use ($user) {
            $q->where('user_id', '=', $user->id);
        });
        $dialog = $dialog->whereHas('members', function ($q) use ($opponent) {
            $q->where('user_id', '=', $opponent->id);
        })->first();

        $data = [
            'id' => $dialog->id,
        ];

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

        $items = $user->certificates()->orderBy('created_at', 'desc')->paginate($this->per_page);

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
                'image' => env('APP_URL') . $item->png_ru,
                'pdf' => env('APP_URL') . $item->pdf_ru
            ];
            $data["items"][] = [
                'id' => $item->id,
                'image' => env('APP_URL') . $item->png_kk,
                'pdf' => env('APP_URL') . $item->pdf_kk
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

        if ($ios_token != null) {
            $user->ios_token = $ios_token;
        }
        if ($android_token != null) {
            $user->android_token = $android_token;
        }
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
        $dialog->is_ts = 1;
        $dialog->save();

        $dialog->members()->sync([$user_id, $tech_support->id]);
    }
}
