<?php

namespace App\Http\Controllers\App\Student;

use App\Http\Controllers\Controller;
use App\Models\Dialog;
use App\Models\PayInformation;
use App\Models\Skill;
use App\Models\StudentInformation;
use App\Models\Type_of_ownership;
use App\Models\User;
use App\Models\UserInformation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;


class UserController extends Controller
{


    public function student_profile()
    {
        $item = StudentInformation::where('user_id', '=', Auth::user()->id)->first();
        return view("app.pages.student.profile.student_profile", [
            "item" => $item
        ]);
    }

    public function update_student_profile(Request $request)
    {
        $request->validate([
            'avatar' => 'required|max:255',
        ]);

        $item = StudentInformation::where('user_id', '=', Auth::user()->getAuthIdentifier())->first();

        if (($request->avatar != $item->avatar)) {
            File::delete(public_path($item->avatar));

            $item->avatar = $request->avatar;
        }

        $item->save();

        return redirect()->back()->with('status', __('default.pages.profile.save_success_message'));
    }


    public function studentAuth()
    {
        if (Auth::check()) {
            return redirect("/" . app()->getLocale() . "/student-profile");
        }
        return view("auth.login_student", [

        ]);
    }

    public function studentLogin(Request $request)
    {
        $client = new Client(['verify' => false]);

        try {
            $body = array("login" => $request->email, "password" => $request->password);
            $response = $client->request('POST', 'https://btest.enbek.kz/ru/api/auth/login', [
                'body' => json_encode($body),
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);
        } catch (BadResponseException $e) {
            return redirect()->back()->with('failed', __('auth.failed'));
        }

        $token = json_decode($response->getBody(), true);
        $token = $token["response"]["token"];
        $student_resume = json_decode($this->getStudentResume($token), true);
        $student_unemployed_status = json_decode($this->getUnemployedStatus($token), true);

        $user = User::where('email', '=', $request->email)->first();
        $student_role = 5;

        if (empty($user)) {

            $item = new User;
            $item->email = $request->email;
            $item->is_activate = 1;
            $item->email_verified_at = Carbon::now()->toDateTimeString();
            $item->save();

            $item->roles()->sync([$student_role]);

            $item_information = new StudentInformation;
            $item_information->user_id = $item->id;
            $item_information->uid = $student_resume[0]["uid"];
            $item_information->profession_code = $student_resume[0]["uozcodprof"];
            if ($student_unemployed_status["response"] == null) {
                $item_information->unemployed_status = 0;
            } else {
                $item_information->unemployed_status = 1;
                $item_information->quota_count = 3;
            }
            $item_information->save();

            $user_skills = array();
            foreach ($student_resume[0]["compSpecList"] as $skill) {
                array_push($user_skills, $skill["codcomp"]);
            }
            $skills = Skill::whereIn('code_skill', $user_skills)->pluck('id')->toArray();
            $item->skills()->sync($skills);

            $this->createTechDialog($user);


            Session::put('student_token', $token);
            Auth::login($item);

        } else {
            if ($user->roles()->first()->id != $student_role) {
                return redirect()->back()->with('status', __('default.pages.auth.student_login_author_exist'));
            }
            Session::put('student_token', $token);
            Auth::login($user);
        }

        return redirect("/" . app()->getLocale());
//        return redirect("/" . app()->getLocale() . "/student-profile");

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

    public function createTechDialog(User $user)
    {
        // Создание диалога с тех.поддержкой
        $tech_support = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'tech_support');
        })->first();

        $dialog = new Dialog;
        $dialog->save();

        $dialog->members()->sync([$user->id, $tech_support->id]);
    }

}
