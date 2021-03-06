<?php

namespace App\Http\Controllers\App\Author;

use App\Exports\ReportingExport;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseAttachments;
use App\Models\Lesson;
use App\Models\Notification;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\StudentCourse;
use App\Models\Theme;
use App\Models\Type_of_ownership;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;


class TestController extends Controller
{

    public function createCourse($lang)
    {
        $skills = Skill::where('fl_show', '=', 1)->where('fl_check', '=', 1)->where('uid', '=', null)->orderBy('name_' . $lang, 'asc')->get();
        return view("app.pages.author.courses.create_course", [
            "items" => [],
            "skills" => $skills
        ]);
    }

    public function storeCourse(Request $request)
    {

        if ($request->is_paid and $request->cost > 0) {
            if ((Auth::user()->payment_info->merchant_login != null) and (Auth::user()->payment_info->merchant_password != null)) {


                $item = new Course;
                $item->name = $request->name;
                $item->author_id = Auth::user()->id;
                $item->lang = $request->lang;
                if ($request->is_paid) {
                    $item->is_paid = 1;
                } else {
                    $item->is_paid = 0;
                }
                if ($request->is_access_all) {
                    $item->is_access_all = 1;
                } else {
                    $item->is_access_all = 0;
                }
                if ($request->is_poor_vision) {
                    $item->is_poor_vision = 1;
                } else {
                    $item->is_poor_vision = 0;
                }
                $item->cost = $request->cost ?? 0;
                $item->profit_desc = $request->profit_desc;
                $item->teaser = $request->teaser;
                $item->description = $request->description;
                $item->course_includes = $request->course_includes;
                $item->certificate_id = $request->certificate_id;

                if (($request->image != $item->image)) {
                    File::delete(public_path($item->image));

                    $item->image = $request->image;
                }

                $item->save();

                $item_attachments = new CourseAttachments;
                $item_attachments->course_id = $item->id;

                // ???????????? ???? ?????????? ??????????
                if ($request->videos_link != [null]) {
                    $item_attachments->videos_link = json_encode($request->videos_link);
                }
                // ???????????? ???? ?????????? ?????????? ?????? ????????????????????????
                if ($request->videos_poor_vision_link != [null]) {
                    $item_attachments->videos_poor_vision_link = json_encode($request->videos_poor_vision_link);
                }
                // ?????????? ?? ????????????????????
                if (($request->videos != $item_attachments->videos)) {
                    File::delete(public_path($item_attachments->videos));

                    $item_attachments->videos = $request->videos;
                }
                // ?????????? ?? ???????????????????? ?????? ????????????????????????
                if (($request->videos_poor_vision != $item_attachments->videos_poor_vision)) {
                    File::delete(public_path($item_attachments->videos_poor_vision));

                    $item_attachments->videos_poor_vision = $request->videos_poor_vision;
                }
                // ?????????? ?? ????????????????????
                if (($request->audios != $item_attachments->audios)) {
                    File::delete(public_path($item_attachments->audios));

                    $item_attachments->audios = $request->audios;
                }
                // ?????????? ?? ???????????????????? ?????? ????????????????????????
                if (($request->audios_poor_vision != $item_attachments->audios_poor_vision)) {
                    File::delete(public_path($item_attachments->audios_poor_vision));

                    $item_attachments->audios = $request->audios_poor_vision;
                }

                $item_attachments->save();

                $item->skills()->sync($request->skills, false);

                return redirect("/" . app()->getLocale() . "/my-courses/drafts")->with('status', __('default.pages.courses.create_request_message'));
            }
            return redirect()->back()->withInput()->with('failed', __('default.pages.profile.pay_info_error'));
        } else {
            $item = new Course;
            $item->name = $request->name;
            $item->author_id = Auth::user()->id;
            $item->lang = $request->lang;
            if ($request->is_paid) {
                $item->is_paid = 1;
            } else {
                $item->is_paid = 0;
            }
            if ($request->is_access_all) {
                $item->is_access_all = 1;
            } else {
                $item->is_access_all = 0;
            }
            if ($request->is_poor_vision) {
                $item->is_poor_vision = 1;
            } else {
                $item->is_poor_vision = 0;
            }
            $item->cost = $request->cost ?? 0;
            $item->profit_desc = $request->profit_desc;
            $item->teaser = $request->teaser;
            $item->description = $request->description;
            $item->course_includes = $request->course_includes;
            $item->certificate_id = $request->certificate_id;

            if (($request->image != $item->image)) {
                File::delete(public_path($item->image));

                $item->image = $request->image;
            }

            $item->save();

            $item_attachments = new CourseAttachments;
            $item_attachments->course_id = $item->id;

            // ???????????? ???? ?????????? ??????????
            if ($request->videos_link != [null]) {
                $item_attachments->videos_link = json_encode($request->videos_link);
            }
            // ???????????? ???? ?????????? ?????????? ?????? ????????????????????????
            if ($request->videos_poor_vision_link != [null]) {
                $item_attachments->videos_poor_vision_link = json_encode($request->videos_poor_vision_link);
            }
            // ?????????? ?? ????????????????????
            if (($request->videos != $item_attachments->videos)) {
                File::delete(public_path($item_attachments->videos));

                $item_attachments->videos = $request->videos;
            }
            // ?????????? ?? ???????????????????? ?????? ????????????????????????
            if (($request->videos_poor_vision != $item_attachments->videos_poor_vision)) {
                File::delete(public_path($item_attachments->videos_poor_vision));

                $item_attachments->videos_poor_vision = $request->videos_poor_vision;
            }
            // ?????????? ?? ????????????????????
            if (($request->audios != $item_attachments->audios)) {
                File::delete(public_path($item_attachments->audios));

                $item_attachments->audios = $request->audios;
            }
            // ?????????? ?? ???????????????????? ?????? ????????????????????????
            if (($request->audios_poor_vision != $item_attachments->audios_poor_vision)) {
                File::delete(public_path($item_attachments->audios_poor_vision));

                $item_attachments->audios = $request->audios_poor_vision;
            }

            $item_attachments->save();

            $item->skills()->sync($request->skills, false);

            return redirect("/" . app()->getLocale() . "/my-courses/drafts")->with('status', __('default.pages.courses.create_request_message'));
        }
    }

    public function myCourses(Request $request)
    {
        $courses_uri = request()->segment(count(request()->segments()));
        switch ($courses_uri) {
            case('my-courses'):
                $query = Auth::user()->courses()->where('status', '=', Course::published);
                $page_name = 'default.pages.courses.my_courses';
                break;
            case('unpublished'):
                $query = Auth::user()->courses()->where('status', '=', Course::unpublished);
                $page_name = 'default.pages.courses.my_courses_unpublished';
                break;
            case('on-check'):
                $query = Auth::user()->courses()->where('status', '=', Course::onCheck);
                $page_name = 'default.pages.courses.my_courses_onCheck';
                break;
            case('drafts'):
                $query = Auth::user()->courses()->where('status', '=', Course::draft);
                $page_name = 'default.pages.courses.drafts';
                break;
            case('deleted'):
                $query = Auth::user()->courses()->where('status', '=', Course::deleted);
                $page_name = 'default.pages.courses.my_courses_deleted';
                break;
        }

        $lang_ru = $request->lang_ru ?? null;
        $lang_kk = $request->lang_kk ?? null;
        $course_type = $request->course_type ?? '';
        $course_sort = $request->course_sort ?? '';
        $min_rating = $request->min_rating ?? 0;
        $members_count = $request->members_count ?? 0;
        $specialities = $request->specialities;
        $skills = $request->skills;

        // ???????????????????? ???? ??????????
        if ($lang_ru == 1 and $lang_kk == null) {
            $query = $query->where('lang', '=', 1);
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->where('lang', '=', 0);
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->whereIn('lang', [0, 1]);
        }
        // ???????????????????? ???? ???????? ??????????
        if ($course_type) {
            if ($course_type == 2) {
                $query = $query->where('is_paid', '=', 0);
            } else if ($course_type == 1) {
                $query = $query->where('is_paid', '=', 1);
            } else if ($course_type == 3) {
                $query = $query->where('quota_status', '=', 2);
            }
        }
        // ???????????????????? ??????????
        if ($course_sort) {
            // ???????????????????? ?????????????? - ???? ??????????????????????
            if ($course_sort == 'sort_by_rate_low') {
                $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                    ->select('course_rate.rate as course_rate', 'courses.*')
                    ->orderBy('course_rate.rate', 'asc');
                // ???????????????????? ?????????????? - ???? ????????????????
            } else if ($course_sort == 'sort_by_rate_low') {
                $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                    ->select('course_rate.rate as course_rate', 'courses.*')
                    ->orderBy('course_rate.rate', 'desc');
                // ???????????????????? ?????????????????? - ???? ????????????????
            } else if ($course_sort == 'sort_by_rate_high') {
                $query->orderBy('cost', 'desc');
                // ???????????????????? ?????????????????? - ???? ??????????????????????
            } else if ($course_sort == 'sort_by_cost_low') {
                $query->orderBy('cost', 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        // ?????????????? ????
        if ($min_rating) {
            $query->whereHas('rate', function ($q) use ($min_rating) {
                $q->where('course_rate.rate', '>=', $min_rating);
            });
        }
        // ????????????????, ???????????????????? ???????? (??????)
        if ($members_count) {
            $query->whereHas('course_members', function ($q) use ($min_rating) {
                $q->where('student_course.is_finished', '=', true)->whereIn('student_course.paid_status', [1, 2]);
            })->withCount([
                'course_members' => function ($q) {
                    $q->whereIn('paid_status', [1, 2]);
                }])->having('course_members_count', '>=', $members_count);
        }
        // ???????????????? ??????????????????
        if ($specialities) {
            $professions = Professions::whereIn('id', $specialities)->get();
        }
        // ???????????????????? ???? ??????????????
        if ($skills) {
            $skills = Skill::whereIn('id', $skills)->get();

            $query->whereHas('skills', function ($q) use ($request) {
                $q->whereIn('skills.id', $request->skills);
            });
        }

        $items = $query->paginate(6);

        return view("app.pages.author.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name,
            "request" => $request,
            "professions" => $professions ?? null,
            "skills" => $skills ?? null
        ]);
    }

    public function courseShow($lang, Course $item)
    {
        $courses = Auth::user()->courses()->get();
        // ?????? ???????????? ???????? ????????????
        $rates = [];
        foreach ($courses as $course) {
            foreach ($course->rate as $rate) {
                array_push($rates, $rate->rate);
            }
        }
        // ?????? ?????????????? ????????????
        $author_students = [];
        foreach ($courses->unique('student_id') as $course) {
            foreach ($course->course_members as $member) {
                array_push($author_students, $member);
            }
        }
        // ?????? ?????????????? ?????????????????????? ????????
        $author_students_finished = [];
        foreach ($courses as $course) {
            foreach ($course->course_members->where('is_finished', '=', true) as $member) {
                array_push($author_students_finished, $member);
            }
        }
        // ???????????? ???????????? ???????????? ???? ???????? ????????????
        if (count($rates) == 0) {
            $average_rates = 0;
        } else {
            $average_rates = array_sum($rates) / count($rates);
        }

//        $data = [['id' => 0, 'name' => "\u0422\u0435\u043C\u0430 1", 'order'=> 0, 'lessons'=> null, 'collapsed'=> !1]];


        if ($item->author_id == Auth::user()->id) {
            $themes = $item->themes()->orderBy('index_number', 'asc')->get();
            $lessons_count = count(Lesson::where('course_id', '=', $item->id)->get());

            $lessons = $item->lessons;
            $videos_count = [];
            $audios_count = [];
            $attachments_count = [];

            foreach ($lessons as $lesson){
                if($lesson->lesson_attachment != null){
                    if($lesson->lesson_attachment->videos != null){
                        $videos_count[] = count(json_decode($lesson->lesson_attachment->videos));
                    }
                    if($lesson->lesson_attachment->audios != null){
                        $audios_count[] = count(json_decode($lesson->lesson_attachment->audios));
                    }
                    if($lesson->lesson_attachment->another_files != null){
                        $attachments_count[] = count(json_decode($lesson->lesson_attachment->another_files));
                    }
                }

            }

            return view("app.pages.author.courses.course", [
                "item" => $item,
                "themes" => $themes,
                "lessons_count" => $lessons_count,
                "rates" => $rates,
                "author_students" => $author_students,
                "courses" => $courses,
                "author_students_finished" => $author_students_finished,
                "average_rates" => $average_rates,
                "videos_count" => array_sum($videos_count),
                "audios_count" => array_sum($audios_count),
                "attachments_count" => array_sum($attachments_count)
            ]);
        } else {
            return redirect("/" . app()->getLocale() . "/my-courses");
        }
    }

    public function editCourse($lang, Course $item)
    {
        if ($item->author_id == Auth::user()->id) {
            switch ($item->status) {
                case 0:
                case 2:
                case 4:
                    $current_skills = Skill::whereHas('courses', function ($query) use ($item) {
                        $query->where('courses.id', '=', $item->id);
                    })->get();
                    $skills = Skill::where('fl_show', '=', 1)->where('fl_check', '=', 1)->where('uid', '=', null)->get();
                    return view("app.pages.author.courses.edit_course", [
                        "item" => $item,
                        "current_skills" => $current_skills,
                        "skills" => $skills
                    ]);
                    break;
                default:
                    return redirect("/" . app()->getLocale() . "/my-courses/course/" . $item->id);
            }

        } else {
            return redirect("/" . app()->getLocale() . "/my-courses");
        }
    }

    public function updateCourse($lang, Request $request, Course $item)
    {
//        $item->name = $request->name;
//        $item->lang = $request->lang;
//        if ($request->is_paid) {
//            $item->is_paid = 1;
//        } else {
//            $item->is_paid = 0;
//        }
//        if ($request->is_access_all) {
//            $item->is_access_all = 1;
//        } else {
//            $item->is_access_all = 0;
//        }
//        if ($request->is_poor_vision) {
//            $item->is_poor_vision = 1;
//        } else {
//            $item->is_poor_vision = 0;
//        }
//        $item->cost = $request->cost;
//        $item->profit_desc = $request->profit_desc;
//        $item->teaser = $request->teaser;
//        $item->description = $request->description;
//
//        $item->certificate_id = $request->certificate_id;
//
//        if (($request->image != $item->image)) {
//            File::delete(public_path($item->image));
//
//            $item->image = $request->image;
//        }
////        $item->status = 1;
//        $item->skills()->sync($request->skills);
//        $item->save();
//
//        $item_attachments = CourseAttachments::where('course_id', '=', $item->id)->first();
//
//        if ($request->videos_link != [null]) {
//            $item_attachments->videos_link = json_encode($request->videos_link);
//        }
//        if ($request->videos_poor_vision_link != [null]) {
//            $item_attachments->videos_poor_vision_link = json_encode($request->videos_poor_vision_link);
//        }
//
//        // ?????????? ?? ????????????????????
//        if (($request->videos != $item_attachments->videos)) {
//            File::delete(public_path($item_attachments->videos));
//
//            $item_attachments->videos = $request->videos;
//        }
//        // ?????????? ?? ???????????????????? ?????? ????????????????????????
//        if (($request->videos_poor_vision != $item_attachments->videos_poor_vision)) {
//            File::delete(public_path($item_attachments->videos_poor_vision));
//
//            $item_attachments->videos_poor_vision = $request->videos_poor_vision;
//        }
//        // ?????????? ?? ????????????????????
//        if (($request->audios != $item_attachments->audios)) {
//            File::delete(public_path($item_attachments->audios));
//
//            $item_attachments->audios = $request->audios;
//        }
//        // ?????????? ?? ???????????????????? ?????? ????????????????????????
//        if (($request->audios_poor_vision != $item_attachments->audios_poor_vision)) {
//            File::delete(public_path($item_attachments->audios_poor_vision));
//
//            $item_attachments->audios = $request->audios_poor_vision;
//        }
//
//        $item_attachments->save();
        return $request->localVideo;

//        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $item->id)->with('status', __('default.pages.profile.save_success_message'));
    }

    public function publishCourse(  Course $item)
    {
        $item->status = 1;
        $item->save();

        return redirect("/" . app()->getLocale() . "/my-courses/on-check")->with('status', __('default.pages.courses.publish_request_message'));
    }

    public function deleteCourse(  Course $item)
    {
        $item->status = 4;
        $item->save();

        return redirect("/" . app()->getLocale() . "/my-courses")->with('status', __('default.pages.courses.delete_request_message'));
    }

    public function quotaConfirm(  Course $item, Request $request)
    {

        $recipients = User::whereHas('roles', function ($q) {
            $q->whereIn('role_id', [1, 6]);
        })->get();
        $author_course = $item->author_id;

        $recipients_array = array();
        foreach ($recipients as $recipient) {
            array_push($recipients_array, $recipient->id);
        }

        if ($request->input('action') == 'confirm') {
            $item->quota_status = 2;
            $item->save();

            $notification = new Notification;
            $notification->name = 'notifications.confirm_quota_description';
            $notification->course_id = $item->id;
            $notification->type = 0;
            $notification->save();

            $notification_1 = new Notification;
            $notification_1->name = 'notifications.course_quota_access';
            $notification_1->course_id = $item->id;
            $notification_1->type = 0;
            $notification_1->save();

            $notification->users()->sync($recipients_array);
            $notification_1->users()->sync([$item->author_id]);

            return redirect()->back()->with('status', trans('notifications.course_quota_access', ['course_name' => '"' . $item->name . '"']));
        } else {
            $item->quota_status = 3;
            $item->save();

            $notification = new Notification;
            $notification->name = 'notifications.reject_quota_description';
            $notification->course_id = $item->id;
            $notification->type = 0;
            $notification->save();

            $notification->users()->sync($recipients_array);
            return redirect()->back()->with('error', trans('notifications.course_quota_access_denied', ['course_name' => '"' . $item->name . '"']));
        }


    }

    public function reestablishCourse(  Course $item)
    {
        $item->status = Course::onCheck;
        $item->save();

        return redirect("/" . app()->getLocale() . "/my-courses")->with('status', __('default.pages.courses.publish_request_message'));
    }

    public function statisticsCourse(Request $request)
    {

        $items = Auth::user()->courses()->get();

        // ?????? ???????????? ???????? ????????????
        $rates = [];
        foreach ($items as $course) {
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
        foreach ($items->unique('student_id') as $course) {
            foreach ($course->course_members as $member) {
                array_push($author_students, $member);
            }
        }
        // ???????? ?????????????????? ????????????
        $all_cost_courses = [];
        $quota_cost_courses = [];
        foreach ($items as $course) {
            foreach ($course->course_members as $member) {
                array_push($all_cost_courses, $member->course->cost);
            }
            foreach ($course->course_members->where('paid_status', '=', '2') as $member) {
                array_push($quota_cost_courses, $member->course->cost);
            }
        }

        $query = Auth::user()->courses()->where('status', '=', Course::published);
        // ???????????????????? ?????????????? - ???? ??????????????????????
        if ($request->sort_by == 'sort_by_rate_low') {
            $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                ->select('course_rate.rate as course_rate', 'courses.*')
                ->orderBy('course_rate.rate', 'asc');
            // ???????????????????? ?????????????? - ???? ????????????????
        } else if ($request->sort_by == 'sort_by_rate_high') {
            $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                ->select('course_rate.rate as course_rate', 'courses.*')
                ->orderBy('course_rate.rate', 'desc');
            // ???????????????????? ???????????????????? ?????????????????????? - ???? ??????????????????????
        } else if ($request->sort_by == 'sort_by_members_count_low') {
            $query->withCount('course_members')->orderBy('course_members_count', 'asc');
            // ???????????????????? ???????????????????? ?????????????????????? - ???? ????????????????
        } else if ($request->sort_by == 'sort_by_members_count_high') {
            $query->withCount('course_members')->orderBy('course_members_count', 'desc');
            // ???????????????????? ???? ??????????????????
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $courses = $query->paginate(8);

        return view("app.pages.author.courses.statistics", [
            'items' => $items,
            'average_rates' => $average_rates,
            'author_students' => $author_students,
            'all_cost_courses' => $all_cost_courses,
            'quota_cost_courses' => $quota_cost_courses,
            'courses' => $courses,
            'request' => $request
        ]);
    }

    public function statisticForChart()
    {

        $items = StudentCourse::whereHas('course', function ($q) {
            $q->where('courses.author_id', '=', Auth::user()->id);
        })->where('paid_status', '!=', 0)->get()->groupBy(function ($val) {
            return Carbon::parse($val->created_at)->format('d');
        });

        $data = [];
        foreach ($items as $item) {
            array_push($data, ["date" => $item->first()->created_at, "value1" => $item->count(), "value2" => $item->where('paid_status', '=', 2)->count()]);
        }

        $json = '{
  "title1": "?????????? ??????????????????",
  "title2": "???????????????????? ???? ????????????",
  "color1": "#00C608",
  "color2": "#F2C94C",
  "data": ' . json_encode($data) . '
}';
        return json_decode($json, true);
    }

    public function reportingCourse(Request $request)
    {
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        $query = Auth::user()->courses();

        if (empty($date_to)) {
            $query = $query->where('updated_at', '>=', date('Y-m-d', strtotime($date_from)));
        } else if (empty($date_from)) {
            $query = $query->where('updated_at', '<=', date('Y-m-d', strtotime($date_to)));
        }

        if (!empty($data_from) and !empty($date_to)) {
            $query = $query->whereBetween('updated_at', [$data_from, $date_to]);
        }

        $items = $query->paginate();

        Session::put('export_reporting', $query->get());

        return view("app.pages.author.courses.reporting", [
            'items' => $items
        ]);
    }

    public function exportReporting(Request $request)
    {
        $query = Session::get('export_reporting');
        $ar = [[]];
        foreach ($query as $i) {
            // ??????????????
            if ($i->is_paid == true) {
                $i->is_paid = __('default.yes_title');
            } else {
                $i->is_paid = __('default.no_title');
            }
            // ??????????
            if ($i->quota_status == 2) {
                $i->quota_status = __('default.yes_title');
            } else {
                $i->quota_status = __('default.no_title');
            }

            $newElement = ['id' => $i['id'], 'author_name' => $i->user->author_info->name, 'name' => $i->name,
                'is_paid' => $i->is_paid, 'quota_status' => $i->quota_status, 'cost' => $i->cost, 'course_members' => count($i->course_members),
                'course_members_quota' => count($i->course_members->where('paid_status', '=', 2)), 'course_members_finished' => count($i->course_members->where('is_finished', '=', true)),
                'course_members_certificate' => count($i->course_members->where('is_finished', '=', true)), 'rate' => count($i->rate),
                'average_rate' => $i->rate->pluck('rate')->avg(), 'count_rate_1' => count($i->rate->where('rate', '=', 1)), 'count_rate_2' => count($i->rate->where('rate', '=', 2)),
                'count_rate_3' => count($i->rate->where('rate', '=', 3)), 'count_rate_4' => count($i->rate->where('rate', '=', 4)), 'count_rate_5' => count($i->rate->where('rate', '=', 5))];
            array_push($ar, $newElement);
        }

        asort($ar);
        return Excel::download(new ReportingExport($ar), '' . __('default.pages.courses.report_title') . '.xlsx');
    }

    public function getCourseData(  Course $course)
    {
        $a = [['id' => 0, 'name' => "???????? 1", 'order' => 0, 'lessons' => [[
            'id' => 0,
            'name' => "\u041A\u0430\u043A \u043F\u0440\u043E\u0445\u043E\u0434\u0438\u0442 \u0434\u0430\u043D\u043D\u044B\u0439 \u043A\u0443\u0440\u0441",
            'order' => 0,
            'duration' => 20
        ]], 'collapsed' => !1]];
        $themes = Theme::where('course_id', '=', $course->id)->with('lessons')->get();

        foreach ($themes as $theme) {
            $theme->order = $theme->index_number;


            foreach ($theme->lessons as $lesson) {
                $lesson->order = $lesson->index_number;
            }
        }

//        [{id: 0, name: "\u0422\u0435\u043C\u0430 1", order: 0, lessons: null, collapsed: !1}, {
//            id: 1,
//            name: "\u0422\u0435\u043C\u0430 2",
//            order: 1,
//            lessons: [{
//                id: 0,
//                name: "\u041A\u0430\u043A \u043F\u0440\u043E\u0445\u043E\u0434\u0438\u0442 \u0434\u0430\u043D\u043D\u044B\u0439 \u043A\u0443\u0440\u0441",
//                order: 0,
//                duration: 20
//            }, {
//                id: 1,
//                name: "\u0420\u0430\u0431\u043E\u0447\u0435\u0435 \u043F\u0440\u043E\u0441\u0442\u0440\u0430\u043D\u0441\u0442\u0432\u043E",
//                order: 1,
//                duration: 40
//            }, {id: 2, name: "\u0423\u0440\u043E\u043A 3", order: 2, duration: 25}, {
//                id: 3,
//                name: "\u0423\u0440\u043E\u043A 4",
//                order: 3,
//                duration: 55
//            }],
//            collapsed: !1
//        }, {id: 2, name: "\u0422\u0435\u043C\u0430 3", order: 2, lessons: null, collapsed: !1}, {
//            id: 3,
//            name: "\u0422\u0435\u043C\u0430 4",
//            order: 3,
//            lessons: null,
//            collapsed: !1
//        }, {id: 4, name: "\u0422\u0435\u043C\u0430 5", order: 4, lessons: null, collapsed: !1}, {
//            id: 5,
//            name: "\u0422\u0435\u043C\u0430 6",
//            order: 5,
//            lessons: [{id: 0, name: "\u0423\u0440\u043E\u043A 1", order: 0, duration: 30}, {
//                id: 1,
//                name: "\u0423\u0440\u043E\u043A 2",
//                order: 1,
//                duration: 15
//            }, {id: 2, name: "\u0423\u0440\u043E\u043A 3", order: 2, duration: 45}],
//            collapsed: !1
//        }]
        return $themes;
    }
}
