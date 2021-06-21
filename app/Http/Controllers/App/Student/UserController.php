<?php

namespace App\Http\Controllers\App\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Dialog;
use App\Models\PayInformation;
use App\Models\Professions;
use App\Models\Role;
use App\Models\Skill;
use App\Models\StudentCertificate;
use App\Models\StudentInformation;
use App\Models\Type_of_ownership;
use App\Models\User;
use App\Models\UserInformation;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use PDF;


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
            'avatar' => 'max:255',
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

    /**
     * Moved to AuthStudent Class!!
     */
    public function studentLogin(Request $request)
    {
        $client = new Client(['verify' => false]);

        try {
            $body = json_encode([
                "login" => $request->email,
                "password" => $request->password
            ]);
            $response = $client->request('POST', config('enbek.base_url').'/ru/api/auth/login', [
                'body' => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);
        } catch (BadResponseException $e) {
            return redirect()->back()->withInput()->with('failed', __('auth.failed'));
        } catch (GuzzleException $b) {
            return redirect()->back()->withInput()->with('failed', __('auth.failed'));
        }

        $authData = json_decode($response->getBody(), true);
        $token = $authData["response"]["token"];
        $uid = $authData["response"]["uid"];

        $studentUnemployedStatus = json_decode($this->getUnemployedStatus($token), true);
        $studentResumes = json_decode($this->getStudentResume($token), true);

        $user = User::whereEmail($request->email)->first();
        $studentRole = Role::whereSlug('student')->first();

        if (empty($user)) {
            $user = new User;
            $user->email = $request->email;
            $user->is_activate = 1;
            $user->email_verified_at = Carbon::now()->toDateTimeString();
            $user->save();

            $user->roles()->sync([$studentRole->id]);

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
            } else {
                Session::put('resume_data', $user->id);
                return redirect()->back();
            }
        }

        if ($user->roles()->first()->id != $studentRole->id) {
            return redirect()->back()->with('status', __('default.pages.auth.student_login_author_exist'));
        }

        Session::put('student_token', $token);
        Auth::login($user);

        return redirect()->back();
//        return redirect("/" . app()->getLocale());
    }

    public function getStudentResume($token)
    {
        $client = new Client(['verify' => false]);

        try {
            $response = $client->request('GET', config('enbek.base_url').'/ru/api/resume-for-obuch', [
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
            $response = $client->request('GET', config('enbek.base_url').'/ru/api/bezrab-for-obuch', [
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

    public function myCertificates()
    {
        $certificates = StudentCertificate::where('user_id', '=', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view("app.pages.student.profile.my_certificates", [
            'items' => $certificates
        ]);

    }

    public function studentDataSave($lang, $user_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'resume_name' => 'required|max:255',
            'resume_iin' => 'required|unique:student_information,iin|numeric|digits:12',
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            Session::put('resume_data', $user_id);
            return redirect()->back()->withErrors($messages)->withInput($request->all());
        }

        StudentInformation::whereUserId($user_id)->update([
            'name' => $request->resume_name,
            'iin' => $request->resume_iin,
            'agree' => 1
        ]);

        $user = User::whereId($user_id)->first();
        Auth::login($user);

        return redirect()->back();
    }

    public function agree($lang, $user_id, Request $request)
    {
        StudentInformation::whereUserId($user_id)->update([
            'agree' => 1
        ]);

        $user = User::whereId($user_id)->first();
        Auth::login($user);

        return redirect()->back();
    }

}
