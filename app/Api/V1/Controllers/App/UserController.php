<?php

namespace App\Api\V1\Controllers\App;

use App\Api\V1\Classes\Message;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Transformers\MessageTransformer;
use App\Models\Course;
use App\Models\Dialog;
use App\Models\Professions;
use App\Models\Role;
use App\Models\Skill;
use App\Models\StudentInformation;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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
        $validator = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required'
        ]);
        $validator = Validator::make($request->header(), [
            'hash' => 'required',
        ]);

        if ($hash = $this->validateHash(['login' => $login, 'password' => $password, 'hash' => $hash], env('APP_DEBUG'))) {
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
            $message = new Message(implode(' ', $errors), 400, null);
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
