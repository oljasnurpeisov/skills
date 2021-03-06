<?php

namespace Libraries\Auth;

use App\Models\Professions;
use App\Models\Role;
use App\Models\Skill;
use App\Models\StudentInformation;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Libraries\Requests\SendRequest;
use Services\Auth\LoginService;
use App\Services\Users\UnemployedService;

class AuthStudent
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $email;
    /**
     * @var int
     */
    private $uid;

    /**
     * AuthStudent constructor.
     *
     * @param string $token
     * @param string $email
     * @param int $uid
     */
    public function __construct(string $token, string $email, int $uid)
    {
        $this->token    = $token;
        $this->email    = $email;
        $this->uid      = $uid;
    }

    /**
     * Перенесено из UserController, studentLogin
     */
    public function afterLogin()
    {
//        $studentUnemployedStatus = json_decode($this->getUnemployedStatus(), true);
        $studentResumes = json_decode($this->getStudentResume(), true);

        $user = User::whereEmail($this->email)->first();
//        $studentRole = Role::whereSlug('student')->first();


        $studentInformation = StudentInformation::whereUserId($user->id)->first();
        if (empty($studentInformation)) {
            $studentInformation = new StudentInformation();
            $studentInformation->user_id = $user->id;
            $studentInformation->uid = $this->uid;

//            if ($studentUnemployedStatus == null) {
//                $studentInformation->unemployed_status = 0;
//            } else {
//                $studentInformation->unemployed_status = 1;
//                $studentInformation->quota_count = 3;
//            }

//            if (($studentResumes != null) && ($studentResumes != [])) {
            if (!empty($studentResumes)) {
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
        if (!empty($studentInformation->iin)) {
            $studentUnemployed = UnemployedService::getStatus($studentInformation->iin, $this->token);
            $studentUnemployedStatus = isset($studentUnemployed['status']) ? 1 : 0;
            $studentUnemployedDate = isset($studentUnemployed['date']) ? Carbon::parse($studentUnemployed['date'])->format('Y-m-d H:i:s') : null;
            if (isset($studentUnemployedStatus) && !empty($studentUnemployedStatus)) {
                $studentInformation->unemployed_status = $studentUnemployedStatus;
                if (isset($studentUnemployedDate) && $studentUnemployedDate > $studentInformation->unemployed_date) {
                    $studentInformation->quota_count = 3;
                }
                $studentInformation->unemployed_date = $studentUnemployedDate;
            }
            $studentInformation->save();
        }
        if (($studentResumes != null) && ($studentResumes != [])) {
            $studentResume = $studentResumes[0];
            $studentInformation->name = $studentResume["FIO"];
            $studentInformation->iin = $studentResume["iin"];
            $studentInformation->coduoz = $studentResume['modresreg_gorod'];
            $studentInformation->region_id = $studentResume['codcato'];
            $studentInformation->save();
            if ($studentInformation->agree !== 1) {
                Session::put('agree_data', $user->id);

                Auth::logout();
            }
        } else {
            if ($studentInformation->name == null && $studentInformation->iin == null && $studentInformation->coduoz == null && $studentInformation->region_id == null) {
                Session::put('resume_data', $user->id);

                Auth::logout();
            } elseif ($studentInformation->coduoz == null || $studentInformation->region_id == null) {
                Session::put('address_data', $user->id);

                Auth::logout();
            }
        }

//        if ($user->roles()->first()->id != $studentRole->id) {
//            return redirect(url('/'))->with('status', __('default.pages.auth.student_login_author_exist'))->send();
//        }

        Session::put('student_token', $this->token);
    }

    /**
     * @return int|string
     * @throws GuzzleException
     */
    private function getStudentResume()
    {
        return (new SendRequest(config('enbek.base_url').'/ru/api/resume-for-obuch', $this->token))->get();
    }
}
