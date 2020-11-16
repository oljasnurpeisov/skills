<?php

namespace App\Http\Controllers\App\Author;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Notification;
use App\Models\Skill;
use App\Models\Theme;
use App\Models\Type_of_ownership;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class CourseController extends Controller
{

    public function createCourse($lang)
    {
        $skills = Skill::where('fl_show', '=', 1)->where('fl_check', '=', 1)->orderBy('name_'.$lang, 'asc')->get();
        return view("app.pages.author.courses.create_course", [
            "items" => [],
            "skills" => $skills
        ]);
    }

    public function storeCourse(Request $request)
    {
        $videos = array();
        $audios = array();

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
        $item->cost = $request->cost;
        $item->profit_desc = $request->profit_desc;
        $item->teaser = $request->teaser;
        $item->description = $request->description;
        $item->course_includes = $request->course_includes;
        if($request->youtube_link != [null]) {
            $item->youtube_link = json_encode($request->youtube_link);
        }
        $item->certificate_id = $request->certificate_id;

        if (!empty($request->image)) {
            File::delete(public_path($item->image));

            $imageName = time() . '.' . $request['image']->getClientOriginalExtension();
            $request['image']->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/courses/images'), $imageName);

            $item->image = '/users/user_' . Auth::user()->getAuthIdentifier() . '/courses/images/' . $imageName;

        }

        if (!empty($request->video)) {
            File::delete(public_path($item->video));

            foreach ($request->video as $video) {
                $videoName = time() . '.' . $video->getClientOriginalExtension();
                $video->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/courses/videos'), $videoName);
                array_push($videos, '/users/user_' . Auth::user()->getAuthIdentifier() . '/courses/videos/' . $videoName);
            }
            $item->video = json_encode($videos);
        }

        if (!empty($request->audio)) {
            File::delete(public_path($item->audio));

            foreach ($request->audio as $audio) {
                $audioName = time() . '.' . $audio->getClientOriginalExtension();
                $audio->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/courses/audios'), $audioName);
                array_push($audios, '/users/user_' . Auth::user()->getAuthIdentifier() . '/courses/audios/' . $audioName);
            }
            $item->audio = json_encode($audios);
        }


        $item->save();
        $item->skills()->sync($request->skills, false);

//        $item->users()->sync([Auth::user()->id]);

        return redirect("/" . app()->getLocale() . "/my-courses/drafts")->with('status', __('default.pages.courses.create_request_message'));
    }

    public function myCourses()
    {
        $items = Course::where('author_id', '=', Auth::user()->id)->where('status', '=', Course::published)->get();
        $page_name = 'default.pages.courses.my_courses';
        return view("app.pages.author.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name
        ]);
    }

    public function myDrafts()
    {
//        $items = Course::whereHas('users', function ($query) {
//            $query->where('users.id', '=', Auth::user()->id);
//        })->where('status', '=', 0)->get();
        $items = Course::where('author_id', '=', Auth::user()->id)->where('status', '=', Course::draft)->get();
        $page_name = 'default.pages.courses.drafts';
        return view("app.pages.author.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name
        ]);
    }

    public function myUnpublishedCourses()
    {
//        $items = Course::whereHas('users', function ($query) {
//            $query->where('users.id', '=', Auth::user()->id);
//        })->where('status', '=', 2)->get();
        $items = Course::where('author_id', '=', Auth::user()->id)->where('status', '=', Course::unpublished)->get();
        $page_name = 'default.pages.courses.my_courses_unpublished';
        return view("app.pages.author.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name
        ]);
    }

    public function myOnCheckCourses()
    {
//        $items = Course::whereHas('users', function ($query) {
//            $query->where('users.id', '=', Auth::user()->id);
//        })->where('status', '=', 1)->get();
        $items = Course::where('author_id', '=', Auth::user()->id)->where('status', '=', Course::onCheck)->get();
        $page_name = 'default.pages.courses.my_courses_onCheck';
        return view("app.pages.author.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name
        ]);
    }

    public function myDeletedCourses()
    {
        $items = Course::where('author_id', '=', Auth::user()->id)->where('status', '=', Course::deleted)->get();
        $page_name = 'default.pages.courses.my_courses_deleted';
        return view("app.pages.author.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name
        ]);
    }

    public function courseShow($lang, Course $item)
    {
        if ($item->author_id == Auth::user()->id) {
            $themes = $item->themes()->orderBy('index_number', 'asc')->get();
            $lessons_count = count(Lesson::where('course_id', '=', $item->id)->get());
            return view("app.pages.author.courses.course", [
                "item" => $item,
                "themes" => $themes,
                "lessons_count" => $lessons_count
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
                    $skills = Skill::where('fl_show', '=', 1)->where('fl_check', '=', 1)->get();
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
        $videos = array();
        $audios = array();

        $item->name = $request->name;
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
        $item->cost = $request->cost;
        $item->profit_desc = $request->profit_desc;
        $item->teaser = $request->teaser;
        $item->description = $request->description;
        $item->course_includes = $request->course_includes;
        if($request->youtube_link != [null]) {
            $item->youtube_link = json_encode($request->youtube_link);
        }
        $item->certificate_id = $request->certificate_id;
//        $item->status = 1;
        // Файлы
        if (!empty($request->image)) {
            File::delete(public_path($item->image));

            $imageName = time() . '.' . $request['image']->getClientOriginalExtension();
            $request['image']->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/courses/images'), $imageName);

            $item->image = '/users/user_' . Auth::user()->getAuthIdentifier() . '/courses/images/' . $imageName;

        }

        if (!empty($request->video)) {
            File::delete(public_path($item->video));

            foreach ($request->video as $video) {
                $videoName = time() . '.' . $video->getClientOriginalExtension();
                $video->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/courses/videos'), $videoName);
                array_push($videos, '/users/user_' . Auth::user()->getAuthIdentifier() . '/courses/videos/' . $videoName);
            }
            $item->video = json_encode($videos);
        }

        if (!empty($request->audio)) {
            File::delete(public_path($item->audio));

            foreach ($request->audio as $audio) {
                $audioName = time() . '.' . $audio->getClientOriginalExtension();
                $audio->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/courses/audios'), $audioName);
                array_push($audios, '/users/user_' . Auth::user()->getAuthIdentifier() . '/courses/audios/' . $audioName);
            }
            $item->audio = json_encode($audios);
        }

        $item->save();

        $item->skills()->sync($request->skills, false);

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $item->id)->with('status', __('default.pages.profile.save_success_message'));
    }

    public function publishCourse($lang, Course $item)
    {
        $item->status = 1;
        $item->save();

        return redirect("/" . app()->getLocale() . "/my-courses/on-check")->with('status', __('default.pages.courses.publish_request_message'));
    }

    public function deleteCourse($lang, Course $item)
    {
        $item->status = 4;
        $item->save();

        return redirect("/" . app()->getLocale() . "/my-courses")->with('status', __('default.pages.courses.delete_request_message'));
    }

    public function quotaConfirm($lang, Course $item, Request $request){

        $recipients = User::whereHas('roles', function($q){
            $q->whereIn('role_id', [1, 6]);
        })->get();
        $author_course = $item->author_id;

        $recipients_array = array();
        foreach ($recipients as $recipient){
            array_push($recipients_array, $recipient->id);
        }

        if ($request->input('action') == 'confirm'){
            $item->quota_status = 2;
            $item->save();

            $notification = new Notification;
            $notification->name = json_encode(array('notifications.confirm_quota_description', $item->id));
            $notification->type = 0;
            $notification->save();

            $notification = new Notification;
            $notification->name = json_encode(array('notifications.course_quota_access', $item->id));
            $notification->type = 0;
            $notification->save();

            array_push($recipients_array, $author_course);

            $notification->users()->sync($recipients_array);

            return redirect("/" . app()->getLocale() . "/my-courses")->with('status',__('notifications.confirm_quota_description'));
        }else{
            $item->quota_status = 3;
            $item->save();

            $notification = new Notification;
            $notification->name = json_encode(array('notifications.reject_quota_description', $item->id));
            $notification->type = 0;
            $notification->save();

            $notification->users()->sync($recipients_array);
            return redirect("/" . app()->getLocale() . "/my-courses")->with('error',__('notifications.course_quota_access_denied'));
        }


    }

    public function reestablishCourse($lang, Course $item)
    {
        $item->status = Course::onCheck;
        $item->save();

        return redirect("/" . app()->getLocale() . "/my-courses")->with('status', __('default.pages.courses.publish_request_message'));
    }

    public function statisticsCourse()
    {

        $items = Auth::user()->courses()->get();

        return view("app.pages.author.courses.statistics", [
            'items' => $items
        ]);
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

        return view("app.pages.author.courses.reporting", [
            'items' => $items
        ]);
    }

}
