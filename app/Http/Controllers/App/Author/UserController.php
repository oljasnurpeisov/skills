<?php

namespace App\Http\Controllers\App\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests\Author\UpdateRequisites;
use App\Models\Bank;
use App\Models\Base;
use App\Models\Course;
use App\Models\OkedActivities;
use App\Models\OkedIndustries;
use App\Models\PayInformation;
use App\Models\Skill;
use App\Models\StudentInformation;
use App\Models\Type_of_ownership;
use App\Models\User;
use App\Models\UserInformation;
use App\Models\UserOkedActivity;
use App\Models\UserOkedIndustry;
use App\Services\Users\UserService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Service\Author\UpdateRequisitesService;
use XmlParser;


class UserController extends Controller
{
    /**
     * @var UpdateRequisitesService
     */
    private $requisitesService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param UpdateRequisitesService $requisitesService
     * @param UserService $userService
     */
    public function __construct(UpdateRequisitesService $requisitesService, UserService $userService)
    {
        $this->requisitesService    = $requisitesService;
        $this->userService          = $userService;
    }

    public function edit_profile()
    {
        $user = User::where('id', '=', Auth::user()->getAuthIdentifier())->with('type_ownership')->first();
        $types_of_ownership = Type_of_ownership::all();
        $notifications = Auth::user()->notifications()->get();

        return view("app.pages.author.profile.edit_profile", [
            "user" => $user,
            "types_of_ownership" => $types_of_ownership,
            "notifications" => $notifications
        ]);
    }

    public function update_profile(Request $request)
    {
        $request->validate([
            'email' => ['required', Rule::unique('users')->ignore($request->user()->id), 'max:255'],
            'iin' => ['required', Rule::unique('users')->ignore($request->user()->id), 'max:12'],
            'company_logo' => 'required|max:255',
            'company_name' => 'required|max:255',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->iin = $request->iin;
        $user->company_name = $request->company_name;
        $user->type_of_ownership = $request->type_of_ownership;

        if ($request->company_logo != $user->company_logo) {
            File::delete(public_path($user->company_logo));

            $user->company_logo = $request->company_logo;
        }

        $user->save();

        return redirect("/" . app()->getLocale() . "/edit-profile")->with('status', __('default.pages.profile.save_success_message'));
    }

    public function change_password()
    {
        $user = Auth::user();
        return view("app.pages.author.profile.change_password", [
            "user" => $user,
        ]);
    }

    public function update_password(Request $request)
    {
        $messages = [
            'different' => __('default.pages.profile.different_password')
        ];

        $request->validate([
            'old_password' => 'required',
            'password' => ['required',
                'min:8',
                'max:20',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
                'different:old_password'],
        ], $messages);

        $user = Auth::user();

        if (Hash::check($request->old_password, $user->password)) {

            $user->password = Hash::make($request['password']);
            $user->save();

            return redirect("/" . app()->getLocale() . "/profile-author-information")->with('status', __('default.pages.profile.password_update_success'));

        } else {
            return redirect()->back()->with('failed', __('default.pages.profile.password_update_failed'));
        }

    }

    public function profile_pay_information()
    {
        $pay_information = PayInformation::where('user_id', '=', Auth::user()->id)->first();
        return view("app.pages.author.profile.profile_pay_information", [
            "pay_information" => $pay_information
        ]);
    }

    public function update_profile_pay_information(Request $request)
    {

        $messages = [
            'max' => [
                'string' => '???????? :attribute ???? ???????????? ?????????????????? :max ????????????????.',
            ]
        ];

        $attributes = [
            'merchant_login' => __('default.pages.profile.merchant_login'),
            'merchant_password' => __('default.pages.profile.merchant_password'),
        ];

        $request->validate([
            'merchant_login' => 'required|max:255',
            'merchant_password' => 'required|max:255',
        ], $messages, $attributes);

        $information = PayInformation::where('user_id', '=', Auth::user()->id)->first();

        if (empty($information)) {

            $item = new PayInformation;
            $item->user_id = Auth::user()->getAuthIdentifier();
            $item->merchant_login = $request->merchant_login;
            $item->merchant_password = $request->merchant_password;

            $item->save();

            return redirect("/" . app()->getLocale() . "/profile-pay-information")->with('status', __('default.pages.profile.save_success_message'));
        } else {

            $item = $information;
            $item->merchant_login = $request->merchant_login;
            $item->merchant_password = $request->merchant_password;
            $item->save();

            return redirect("/" . app()->getLocale() . "/profile-pay-information")->with('status', __('default.pages.profile.save_success_message'));
        }

    }

    /**
     * Requisites
     *
     * @author kgurovoy@gmail.com
     *
     * @return View
     */
    public function profile_requisites(): View
    {
        return view("app.pages.author.profile.profile_requisites", [
            'types_of_ownership'    => Type_of_ownership::get(),
            'banks'                 => Bank::get(),
            'bases'                 => Base::get()
        ]);
    }

    /**
     * Update user requisites
     *
     * @author kgurovoy@gmail.com
     *
     * @param UpdateRequisites $request
     * @return RedirectResponse
     */
    public function update_profile_requisites(UpdateRequisites $request): RedirectResponse
    {
        $this->requisitesService->update(Auth::user()->id, $request->all());

        return redirect()->back();
    }

    public function author_data_show()
    {
        $item = UserInformation::where('user_id', '=', Auth::user()->id)->first();
        $courses = Auth::user()->courses()->get();
        // ?????? ???????????? ???????? ????????????
        $rates = [];
        foreach ($courses as $course) {
            foreach ($course->rate as $rate) {
                array_push($rates, $rate->rate);
            }
        }
        // ???????????? ???????????? ???????????? ???? ???????? ????????????
        if (count($rates) == 0) {
            $average_rates = 0;
        } else {
            $average_rates = array_sum($rates) / count($rates);
        }
        // ?????? ?????????????? ????????????
        $author_students = [];
        foreach ($courses as $course) {
            foreach ($course->course_members as $member) {
                $author_students[$member['student_id']][] = $member;
            }
        }
        // ?????? ?????????????? ?????????????????????? ????????
        $author_students_finished = [];
        foreach ($courses as $course) {
            foreach ($course->course_members->where('is_finished', '=', true) as $member) {
                array_push($author_students_finished, $member);
            }
        }
        return view("app.pages.author.profile.author_data_profile", [
            'item' => $item,
            "courses" => $courses,
            "rates" => $rates,
            "average_rates" => $average_rates,
            "author_students" => $author_students,
            "author_students_finished" => $author_students_finished,
            "okedIndustriesSelected" => UserOkedIndustry::whereUserId(Auth::user()->id)->with('oked_industry')->get(),
            "okedActivitiesSelected" => UserOkedActivity::whereUserId(Auth::user()->id)->with('oked_activity')->get()
        ]);
    }

    /**
     * ?????????????????? ???????????????? ????????
     *
     * @param Request $request
     * @param $lang
     * @return mixed
     */
    public function getOkedIndustries(Request $request, $lang)
    {
        $industries = OkedIndustries::orderBy('name_' . $lang, 'asc');

        if (!empty($request->oked_activities)) {
            $industries = $industries->whereHas('oked_activities', function ($q) use ($request) {
                return $q->whereIn('id', $request->oked_activities);
            });
        }

        if (!empty($request->name)) {
            $industries = $industries->where('name_' . $lang, 'like', '%' . $request->name . '%');
        }

        return $industries->paginate(50, ['*'], 'page', $request->page ?? 1);
    }

    /**
     * ?????????????????? ?????????? ???????????????????????? ????????
     *
     * @param Request $request
     * @param $lang
     * @return mixed
     */
    public function getOkedActivities(Request $request, $lang)
    {
        $activities = OkedActivities::orderBy('name_' . $lang, 'asc');

        if (!empty($request->oked_industries)) {
            $activities = $activities->whereIn('oked_industries_id', $request->oked_industries);
        }

        if (!empty($request->name)) {
            $activities = $activities->where('name_' . $lang, 'like', '%' . $request->name ?? '' . '%');
        }

        return $activities->paginate(50, ['*'], 'page', $request->page ?? 1);
    }

    public function update_author_data_profile(Request $request)
    {
        $messages = [
            'max' => [
                'string' => '???????? :attribute ???? ???????????? ?????????????????? :max ????????????????.',
            ]
        ];

        $attributes = [
            'name' => __('default.pages.profile.name'),
            'surname' => __('default.pages.profile.surname'),
            'avatar' => __('default.pages.profile.avatar'),
//            'specialization' => __('default.pages.profile.specialization'),
            'about' => __('default.pages.profile.about'),
            'phone_1' => __('default.pages.profile.phone_1'),
            'phone_2' => __('default.pages.profile.phone_2'),
            'site_url' => __('default.pages.profile.site_url'),
            'vk_link' => __('default.pages.profile.vk_link'),
            'fb_link' => __('default.pages.profile.fb_link'),
            'instagram_link' => __('default.pages.profile.instagram_link'),
        ];

        $request->validate([
            'name' => 'max:255',
            'surname' => 'max:255',
            'avatar' => 'max:255',
//            'specialization' => 'max:255',
            'phone_1' => 'max:255',
            'phone_2' => 'max:255',
            'site_url' => 'max:255',
            'vk_link' => 'max:255',
            'fb_link' => 'max:255',
            'instagram_link' => 'max:255',
        ], $messages, $attributes);

        $information = UserInformation::where('user_id', '=', Auth::user()->getAuthIdentifier())->first();

        if (empty($information)) {
            $item = new UserInformation;
        } else {
            $item = $information;
        }

        $item->user_id = Auth::user()->getAuthIdentifier();
        $item->name = $request->name;
        $item->surname = $request->surname;
//        $item->specialization = json_encode($request->specialization, JSON_UNESCAPED_UNICODE);
        $item->about = $request->about;
        $item->phone_1 = $request->phone_1;
        $item->phone_2 = $request->phone_2;
        $item->site_url = $request->site_url;
        $item->vk_link = $request->vk_link;
        $item->fb_link = $request->fb_link;
        $item->instagram_link = $request->instagram_link;

        if (($request->avatar != $item->avatar)) {
            File::delete(public_path($item->avatar));

            $item->avatar = $request->avatar;
        }

        $certificates = array_merge(json_decode($request->certificates) ?? [], $request->certificatesStored ?? []);

        if ($certificates != $item->certificates) {

            $item->certificates = $certificates;

        }

        $item->save();


        $this->userService->saveOked($request->oked_industries, $request->oked_activities);


        return redirect("/" . app()->getLocale() . "/profile-author-information")->with('status', __('default.pages.profile.save_success_message'));

    }

}
