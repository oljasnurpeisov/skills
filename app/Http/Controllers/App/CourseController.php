<?php

namespace App\Http\Controllers\App;

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

    public function createCourse()
    {
        $skills = Skill::where('fl_show', '=', 1)->where('fl_check', '=', null)->get();
        return view("app.pages.page.courses.create_course", [
            "items" => [],
            "skills" => $skills
        ]);
    }

    public function storeCourse(Request $request)
    {
        $item = new Course;
        $item->name = $request->name;
        $item->skills = json_encode($request->skills);
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
        $item->youtube_link = $request->youtube_link;
        $item->certificate_id = $request->certificate_id;

        if (!empty($request->image)) {
            File::delete(public_path($item->image));

            $imageName = time() . '.' . $request['image']->getClientOriginalExtension();
            $request['image']->move(public_path('images/courses_images'), $imageName);
            $item->image = '/images/courses_images/' . $imageName;
        }

        if (!empty($request->video)) {
            File::delete(public_path($item->video));

            $imageName = time() . '.' . $request['video']->getClientOriginalExtension();
            $request['video']->move(public_path('files/courses_videos'), $imageName);
            $item->video = '/files/courses_videos/' . $imageName;
        }

        if (!empty($request->audio)) {
            File::delete(public_path($item->audio));

            $imageName = time() . '.' . $request['audio']->getClientOriginalExtension();
            $request['audio']->move(public_path('files/courses_audio'), $imageName);
            $item->audio = '/files/courses_audio/' . $imageName;
        }

        $item->save();

        $item->users()->sync([Auth::user()->id]);

        return redirect("/" . app()->getLocale() . "/my-courses/drafts");
    }

    public function myCourses()
    {
        $items = Course::whereHas('users', function ($query) {
            $query->where('users.id', '=', Auth::user()->id);
        })->where('status', '=', 3)->get();
        $page_name = 'default.pages.courses.my_courses';
        return view("app.pages.page.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name
        ]);
    }

    public function myDrafts()
    {
        $items = Course::whereHas('users', function ($query) {
            $query->where('users.id', '=', Auth::user()->id);
        })->where('status', '=', 0)->get();
        $page_name = 'default.pages.courses.drafts';
        return view("app.pages.page.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name
        ]);
    }

    public function myUnpublishedCourses()
    {
        $items = Course::whereHas('users', function ($query) {
            $query->where('users.id', '=', Auth::user()->id);
        })->where('status', '=', 2)->get();
        $page_name = 'default.pages.courses.my_courses_unpublished';
        return view("app.pages.page.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name
        ]);
    }

    public function myOnCheckCourses()
    {
        $items = Course::whereHas('users', function ($query) {
            $query->where('users.id', '=', Auth::user()->id);
        })->where('status', '=', 1)->get();
        $page_name = 'default.pages.courses.my_courses_onCheck';
        return view("app.pages.page.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name
        ]);
    }

    public function myDeletedCourses()
    {
        $items = Course::whereHas('users', function ($query) {
            $query->where('users.id', '=', Auth::user()->id);
        })->where('status', '=', 4)->get();
        $page_name = 'default.pages.courses.my_courses_deleted';
        return view("app.pages.page.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name
        ]);
    }

    public function courseShow($lang, Course $item)
    {
        if ($item->users()->first()->id == Auth::user()->id) {
            $themes = $item->themes()->orderBy('index_number', 'asc')->get();
            $lessons_count = 0;
            foreach ($themes as $theme){
                foreach($theme->lessons()->get() as $lesson){
                    $lessons_count++;
                }
            }
//            $lessons = $themes[0]->lessons()->get();
            return view("app.pages.page.courses.course", [
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
        if ($item->users()->first()->id == Auth::user()->id) {
            switch ($item->status) {
                case 0:
                case 2:
                case 4:
                    $current_skills = json_decode($item->skills);
                    $skills = Skill::where('fl_show', '=', 1)->where('fl_check', '=', null)->get();
                    return view("app.pages.page.courses.edit_course", [
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
        $item->name = $request->name;
        $item->skills = json_encode($request->skills);
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
        $item->youtube_link = $request->youtube_link;
        $item->certificate_id = $request->certificate_id;
        $item->status = 1;
        // Файлы
        if (!empty($request->image)) {
            File::delete(public_path($item->image));

            $imageName = time() . '.' . $request['image']->getClientOriginalExtension();
            $request['image']->move(public_path('images/courses_images'), $imageName);
            $item->image = '/images/courses_images/' . $imageName;
        }

        if (!empty($request->video)) {
            File::delete(public_path($item->video));

            $videoName = time() . '.' . $request['video']->getClientOriginalExtension();
            $request['video']->move(public_path('files/courses_videos'), $videoName);
            $item->video = '/files/courses_videos/' . $videoName;
        }

        if (!empty($request->audio)) {
            File::delete(public_path($item->audio));

            $audioName = time() . '.' . $request['audio']->getClientOriginalExtension();
            $request['audio']->move(public_path('files/courses_audio'), $audioName);
            $item->audio = '/files/courses_audio/' . $audioName;
        }

        $item->save();

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $item->id);
    }

    public function publishCourse($lang, Course $item)
    {
        $item->status = 1;
        $item->save();

        return redirect("/" . app()->getLocale() . "/my-courses/on-check");
    }

    public function deleteCourse($lang, Course $item)
    {
        $item->status = 4;
        $item->save();

        return redirect("/" . app()->getLocale() . "/my-courses");
    }

    public function quotaConfirm($lang, Course $item, Request $request){

        $recipients = User::whereHas('roles', function($q){
            $q->whereIn('role_id', [1, 6]);
        })->get();
        $author_course = $item->users()->first()->id;

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

            array_push($recipients_array, $author_course);

            $notification->users()->sync($recipients_array);
        }else{
            $item->quota_status = 3;
            $item->save();

            $notification = new Notification;
            $notification->name = json_encode(array('notifications.reject_quota_description', $item->id));
            $notification->type = 0;
            $notification->save();

            $notification->users()->sync($recipients_array);
        }


        return redirect()->back();
    }

}
