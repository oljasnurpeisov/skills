<?php

namespace App\Http\Controllers\App\Author;

use App\Exports\ReportingExport;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Notification;
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


class CourseController extends Controller
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
        if ($request->youtube_link != [null]) {
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

    public function myCourses(Request $request)
    {
        $lang_ru = $request->lang_ru ?? null;
        $lang_kk = $request->lang_kk ?? null;
        $course_type = $request->course_type ?? '';
        $course_sort = $request->course_sort ?? '';
        $min_rating = $request->min_rating ?? 0;
        $members_count = $request->members_count ?? 0;

        $query = Auth::user()->courses()->where('status', '=', Course::published);
        // Сортировка по языку
        if ($lang_ru == 1 and $lang_kk == null) {
            $query = $query->where('lang', '=', 1);
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->where('lang', '=', 0);
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->whereIn('lang', [0, 1]);
        }
        // Сортировка по виду курса
        if ($course_type) {
            if ($course_type == 2) {
                $query = $query->where('is_paid', '=', 0);
            } else if ($course_type == 1) {
                $query = $query->where('is_paid', '=', 1);
            } else if ($course_type == 3) {
                $query = $query->where('quota_status', '=', 2);
            }
        }
        // Сортировка курса
        if ($course_sort) {
            // Сортировка Рейтинг - по возрастанию
            if ($course_sort == 'sort_by_rate_low') {
                $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                    ->select('course_rate.rate as course_rate', 'courses.*')
                    ->orderBy('course_rate.rate', 'asc');
                // Сортировка Рейтинг - по убыванию
            } else if ($course_sort == 'sort_by_rate_low') {
                $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                    ->select('course_rate.rate as course_rate', 'courses.*')
                    ->orderBy('course_rate.rate', 'desc');
                // Сортировка Стоимость - по убыванию
            } else if ($course_sort == 'sort_by_rate_high') {
                $query->orderBy('cost', 'desc');
                // Сортировка Стоимость - по возрастанию
            } else if ($course_sort == 'sort_by_cost_low') {
                $query->orderBy('cost', 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        // Рейтинг от
        if ($min_rating) {
            $query->whereHas('rate', function ($q) use ($min_rating) {
                $q->where('course_rate.rate', '>=', $min_rating);
            });
        }
        // Учеников, окончивших курс (мин)
        if ($members_count) {
            $query->whereHas('course_members', function ($q) use ($min_rating) {
                $q->where('student_course.is_finished', '=', true)->whereIn('student_course.paid_status', [1, 2]);
            })->withCount([
                'course_members' => function ($q) {
                    $q->whereIn('paid_status', [1, 2]);
                }])->having('course_members_count', '>=', $members_count);
        }

        $items = $query->paginate(6);

        $page_name = 'default.pages.courses.my_courses';
        return view("app.pages.author.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name,
            "request" => $request
        ]);
//        return json_encode($items);
    }

    public function myDrafts(Request $request)
    {
        $lang_ru = $request->lang_ru ?? null;
        $lang_kk = $request->lang_kk ?? null;
        $course_type = $request->course_type ?? '';
        $course_sort = $request->course_sort ?? '';
        $min_rating = $request->min_rating ?? 0;
        $members_count = $request->members_count ?? 0;

        $query = Auth::user()->courses()->where('status', '=', Course::draft);
        // Сортировка по языку
        if ($lang_ru == 1 and $lang_kk == null) {
            $query = $query->where('lang', '=', 1);
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->where('lang', '=', 0);
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->whereIn('lang', [0, 1]);
        }
        // Сортировка по виду курса
        if ($course_type) {
            if ($course_type == 2) {
                $query = $query->where('is_paid', '=', 0);
            } else if ($course_type == 1) {
                $query = $query->where('is_paid', '=', 1);
            } else if ($course_type == 3) {
                $query = $query->where('quota_status', '=', 2);
            }
        }
        // Сортировка курса
        if ($course_sort) {
            // Сортировка Рейтинг - по возрастанию
            if ($course_sort == 'sort_by_rate_low') {
                $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                    ->select('course_rate.rate as course_rate', 'courses.*')
                    ->orderBy('course_rate.rate', 'asc');
                // Сортировка Рейтинг - по убыванию
            } else if ($course_sort == 'sort_by_rate_low') {
                $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                    ->select('course_rate.rate as course_rate', 'courses.*')
                    ->orderBy('course_rate.rate', 'desc');
                // Сортировка Стоимость - по убыванию
            } else if ($course_sort == 'sort_by_rate_high') {
                $query->orderBy('cost', 'desc');
                // Сортировка Стоимость - по возрастанию
            } else if ($course_sort == 'sort_by_cost_low') {
                $query->orderBy('cost', 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        // Рейтинг от
        if ($min_rating) {
            $query->whereHas('rate', function ($q) use ($min_rating) {
                $q->where('course_rate.rate', '>=', $min_rating);
            });
        }
        // Учеников, окончивших курс (мин)
        if ($members_count) {
            $query->whereHas('course_members', function ($q) use ($min_rating) {
                $q->where('student_course.is_finished', '=', true)->whereIn('student_course.paid_status', [1, 2]);
            })->withCount([
                'course_members' => function ($q) {
                    $q->whereIn('paid_status', [1, 2]);
                }])->having('course_members_count', '>=', $members_count);
        }

        $items = $query->paginate(6);
        $page_name = 'default.pages.courses.drafts';
        return view("app.pages.author.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name,
            "request" => $request
        ]);
    }

    public function myUnpublishedCourses(Request $request)
    {
        $lang_ru = $request->lang_ru ?? null;
        $lang_kk = $request->lang_kk ?? null;
        $course_type = $request->course_type ?? '';
        $course_sort = $request->course_sort ?? '';
        $min_rating = $request->min_rating ?? 0;
        $members_count = $request->members_count ?? 0;

        $query = Auth::user()->courses()->where('status', '=', Course::unpublished);
        // Сортировка по языку
        if ($lang_ru == 1 and $lang_kk == null) {
            $query = $query->where('lang', '=', 1);
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->where('lang', '=', 0);
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->whereIn('lang', [0, 1]);
        }
        // Сортировка по виду курса
        if ($course_type) {
            if ($course_type == 2) {
                $query = $query->where('is_paid', '=', 0);
            } else if ($course_type == 1) {
                $query = $query->where('is_paid', '=', 1);
            } else if ($course_type == 3) {
                $query = $query->where('quota_status', '=', 2);
            }
        }
        // Сортировка курса
        if ($course_sort) {
            // Сортировка Рейтинг - по возрастанию
            if ($course_sort == 'sort_by_rate_low') {
                $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                    ->select('course_rate.rate as course_rate', 'courses.*')
                    ->orderBy('course_rate.rate', 'asc');
                // Сортировка Рейтинг - по убыванию
            } else if ($course_sort == 'sort_by_rate_low') {
                $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                    ->select('course_rate.rate as course_rate', 'courses.*')
                    ->orderBy('course_rate.rate', 'desc');
                // Сортировка Стоимость - по убыванию
            } else if ($course_sort == 'sort_by_rate_high') {
                $query->orderBy('cost', 'desc');
                // Сортировка Стоимость - по возрастанию
            } else if ($course_sort == 'sort_by_cost_low') {
                $query->orderBy('cost', 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        // Рейтинг от
        if ($min_rating) {
            $query->whereHas('rate', function ($q) use ($min_rating) {
                $q->where('course_rate.rate', '>=', $min_rating);
            });
        }
        // Учеников, окончивших курс (мин)
        if ($members_count) {
            $query->whereHas('course_members', function ($q) use ($min_rating) {
                $q->where('student_course.is_finished', '=', true)->whereIn('student_course.paid_status', [1, 2]);
            })->withCount([
                'course_members' => function ($q) {
                    $q->whereIn('paid_status', [1, 2]);
                }])->having('course_members_count', '>=', $members_count);
        }

        $items = $query->paginate(6);
        $page_name = 'default.pages.courses.my_courses_unpublished';
        return view("app.pages.author.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name,
            "request" => $request
        ]);
    }

    public function myOnCheckCourses(Request $request)
    {
        $lang_ru = $request->lang_ru ?? null;
        $lang_kk = $request->lang_kk ?? null;
        $course_type = $request->course_type ?? '';
        $course_sort = $request->course_sort ?? '';
        $min_rating = $request->min_rating ?? 0;
        $members_count = $request->members_count ?? 0;

        $query = Auth::user()->courses()->where('status', '=', Course::onCheck);
        // Сортировка по языку
        if ($lang_ru == 1 and $lang_kk == null) {
            $query = $query->where('lang', '=', 1);
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->where('lang', '=', 0);
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->whereIn('lang', [0, 1]);
        }
        // Сортировка по виду курса
        if ($course_type) {
            if ($course_type == 2) {
                $query = $query->where('is_paid', '=', 0);
            } else if ($course_type == 1) {
                $query = $query->where('is_paid', '=', 1);
            } else if ($course_type == 3) {
                $query = $query->where('quota_status', '=', 2);
            }
        }
        // Сортировка курса
        if ($course_sort) {
            // Сортировка Рейтинг - по возрастанию
            if ($course_sort == 'sort_by_rate_low') {
                $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                    ->select('course_rate.rate as course_rate', 'courses.*')
                    ->orderBy('course_rate.rate', 'asc');
                // Сортировка Рейтинг - по убыванию
            } else if ($course_sort == 'sort_by_rate_low') {
                $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                    ->select('course_rate.rate as course_rate', 'courses.*')
                    ->orderBy('course_rate.rate', 'desc');
                // Сортировка Стоимость - по убыванию
            } else if ($course_sort == 'sort_by_rate_high') {
                $query->orderBy('cost', 'desc');
                // Сортировка Стоимость - по возрастанию
            } else if ($course_sort == 'sort_by_cost_low') {
                $query->orderBy('cost', 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        // Рейтинг от
        if ($min_rating) {
            $query->whereHas('rate', function ($q) use ($min_rating) {
                $q->where('course_rate.rate', '>=', $min_rating);
            });
        }
        // Учеников, окончивших курс (мин)
        if ($members_count) {
            $query->whereHas('course_members', function ($q) use ($min_rating) {
                $q->where('student_course.is_finished', '=', true)->whereIn('student_course.paid_status', [1, 2]);
            })->withCount([
                'course_members' => function ($q) {
                    $q->whereIn('paid_status', [1, 2]);
                }])->having('course_members_count', '>=', $members_count);
        }

        $items = $query->paginate(6);
        $page_name = 'default.pages.courses.my_courses_onCheck';
        return view("app.pages.author.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name,
            "request" => $request
        ]);
    }

    public function myDeletedCourses(Request $request)
    {
        $lang_ru = $request->lang_ru ?? null;
        $lang_kk = $request->lang_kk ?? null;
        $course_type = $request->course_type ?? '';
        $course_sort = $request->course_sort ?? '';
        $min_rating = $request->min_rating ?? 0;
        $members_count = $request->members_count ?? 0;

        $query = Auth::user()->courses()->where('status', '=', Course::deleted);
        // Сортировка по языку
        if ($lang_ru == 1 and $lang_kk == null) {
            $query = $query->where('lang', '=', 1);
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->where('lang', '=', 0);
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->whereIn('lang', [0, 1]);
        }
        // Сортировка по виду курса
        if ($course_type) {
            if ($course_type == 2) {
                $query = $query->where('is_paid', '=', 0);
            } else if ($course_type == 1) {
                $query = $query->where('is_paid', '=', 1);
            } else if ($course_type == 3) {
                $query = $query->where('quota_status', '=', 2);
            }
        }
        // Сортировка курса
        if ($course_sort) {
            // Сортировка Рейтинг - по возрастанию
            if ($course_sort == 'sort_by_rate_low') {
                $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                    ->select('course_rate.rate as course_rate', 'courses.*')
                    ->orderBy('course_rate.rate', 'asc');
                // Сортировка Рейтинг - по убыванию
            } else if ($course_sort == 'sort_by_rate_low') {
                $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                    ->select('course_rate.rate as course_rate', 'courses.*')
                    ->orderBy('course_rate.rate', 'desc');
                // Сортировка Стоимость - по убыванию
            } else if ($course_sort == 'sort_by_rate_high') {
                $query->orderBy('cost', 'desc');
                // Сортировка Стоимость - по возрастанию
            } else if ($course_sort == 'sort_by_cost_low') {
                $query->orderBy('cost', 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        // Рейтинг от
        if ($min_rating) {
            $query->whereHas('rate', function ($q) use ($min_rating) {
                $q->where('course_rate.rate', '>=', $min_rating);
            });
        }
        // Учеников, окончивших курс (мин)
        if ($members_count) {
            $query->whereHas('course_members', function ($q) use ($min_rating) {
                $q->where('student_course.is_finished', '=', true)->whereIn('student_course.paid_status', [1, 2]);
            })->withCount([
                'course_members' => function ($q) {
                    $q->whereIn('paid_status', [1, 2]);
                }])->having('course_members_count', '>=', $members_count);
        }

        $items = $query->paginate(6);

        $page_name = 'default.pages.courses.my_courses_deleted';
        return view("app.pages.author.courses.my_courses", [
            "items" => $items,
            "page_name" => $page_name,
            "request" => $request
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
        if ($request->youtube_link != [null]) {
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

    public function quotaConfirm($lang, Course $item, Request $request)
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

    public function reestablishCourse($lang, Course $item)
    {
        $item->status = Course::onCheck;
        $item->save();

        return redirect("/" . app()->getLocale() . "/my-courses")->with('status', __('default.pages.courses.publish_request_message'));
    }

    public function statisticsCourse(Request $request)
    {

        $items = Auth::user()->courses()->get();

        // Все оценки всех курсов
        $rates = [];
        foreach ($items as $course) {
            foreach ($course->rate as $rate) {
                array_push($rates, $rate->rate);
            }
        }

        // Оценка автора исходя из всех оценок
        if (count($rates) == 0) {
            $average_rates = 0;
        } else {
            $average_rates = array_sum($rates) / count($rates);
        }
        // Все ученики автора
        $author_students = [];
        foreach ($items->unique('student_id') as $course) {
            foreach ($course->course_members as $member) {
                array_push($author_students, $member);
            }
        }
        // Цена купленных курсов
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
        // Сортировка Рейтинг - по возрастанию
        if ($request->sort_by == 'sort_by_rate_low') {
            $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                ->select('course_rate.rate as course_rate', 'courses.*')
                ->orderBy('course_rate.rate', 'asc');
            // Сортировка Рейтинг - по убыванию
        } else if ($request->sort_by == 'sort_by_rate_high') {
            $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                ->select('course_rate.rate as course_rate', 'courses.*')
                ->orderBy('course_rate.rate', 'desc');
            // Сортировка Количество обучающихся - по возрастанию
        } else if ($request->sort_by == 'sort_by_members_count_low') {
            $query->withCount('course_members')->orderBy('course_members_count', 'asc');
            // Сортировка Количество обучающихся - по убыванию
        } else if ($request->sort_by == 'sort_by_members_count_high') {
            $query->withCount('course_members')->orderBy('course_members_count', 'desc');
            // Сортировка По умолчанию
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

//        $items = StudentCourse::whereHas('course', function ($q) {
//            $q->where('courses.author_id', '=', Auth::user()->id);
//        })->where('paid_status', '!=', 0)->get()->groupBy(function ($val) {
//            return Carbon::parse($val->created_at)->format('d');
//        });

        $items = StudentCourse::whereHas('course', function ($q) {
            $q->where('courses.author_id', '=', Auth::user()->id);
        })->where('paid_status', '!=', 0)->get()->groupBy(function ($val) {
            return Carbon::parse($val->created_at)->format('d');
        });

        $value1 = StudentCourse::where('paid_status', '=', 1)->get()->groupBy(function ($val) {
            return Carbon::parse($val->created_at)->format('d');
        });
        $value2 = count(StudentCourse::where('paid_status', '=', 2)->get());


//        $items = StudentCourse::whereHas('course', function ($q) {
//            $q->where('courses.author_id', '=', Auth::user()->id);
//        })->where('paid_status', '!=', 0)->get();

        $data = [];

        $json = '{
  "title1": "Общий заработок",
  "title2": "Заработано по квотам",
  "color1": "#00C608",
  "color2": "#F2C94C",
  "data": ' . json_encode($data) . '
}';
//        return json_decode($json, true);
        return $value1;
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
            // Платный
            if ($i->is_paid == true) {
                $i->is_paid = __('default.yes_title');
            } else {
                $i->is_paid = __('default.no_title');
            }
            // Квота
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

}
