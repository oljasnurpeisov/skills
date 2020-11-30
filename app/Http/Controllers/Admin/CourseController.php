<?php

namespace App\Http\Controllers\Admin;

//use App\Helpers\Buffet;
//use App\Models\Card;
//use App\Models\Company;
use App\Extensions\RandomStringGenerator;
use App\Mail\QuotaMessage;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Notification;
use App\Models\PayInformation;
use App\Models\Role;
use App\Models\Theme;
use App\Models\Type_of_ownership;
use App\Models\User;
use App\Models\Log;

use App\Models\UserInformation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

/**
 * --------------------------------------------------------------------------
 *  UserController
 * --------------------------------------------------------------------------
 *
 *  Этот контроллер отвечает за редактирование данных пользователя
 *
 */
class CourseController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = Course::orderBy('id', 'desc')->where('status', '!=', Course::draft)->where('status', '!=', Course::deleted);
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $query->where('name', 'like', '%' . $term . '%');
        $items = $query->paginate();

        return view('admin.v2.pages.courses.index', [
            'items' => $items,
            'term' => $term,
        ]);
    }

    public function wait_verification(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = Course::orderBy('id', 'desc')->where('status', '=', Course::onCheck);
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $query->where('name', 'like', '%' . $term . '%');
        $items = $query->paginate();

        return view('admin.v2.pages.courses.index', [
            'items' => $items,
            'term' => $term,
        ]);
    }

    public function unpublished_index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = Course::orderBy('id', 'desc')->where('status', '=', Course::unpublished);
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $query->where('name', 'like', '%' . $term . '%');
        $items = $query->paginate();

        return view('admin.v2.pages.courses.index', [
            'items' => $items,
            'term' => $term,
        ]);
    }

    public function published_index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = Course::orderBy('id', 'desc')->where('status', '=', Course::published);
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $query->where('name', 'like', '%' . $term . '%');
        $items = $query->paginate();

        return view('admin.v2.pages.courses.index', [
            'items' => $items,
            'term' => $term,
        ]);
    }

    public function drafts_index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = Course::orderBy('id', 'desc')->where('status', '=', Course::draft);
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $query->where('name', 'like', '%' . $term . '%');
        $items = $query->paginate();

        return view('admin.v2.pages.courses.index', [
            'items' => $items,
            'term' => $term,
        ]);
    }

    public function deleted_index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = Course::orderBy('id', 'desc')->where('status', '=', Course::deleted);
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $query->where('name', 'like', '%' . $term . '%');
        $items = $query->paginate();

        return view('admin.v2.pages.courses.index', [
            'items' => $items,
            'term' => $term,
        ]);
    }

    public function view($lang, Course $item)
    {
        if ($item->status == 1 or $item->status == 2 or $item->status == 3) {

            return view('admin.v2.pages.courses.view', [
                'item' => $item
            ]);
        }
        return redirect('/' . $lang . '/admin/courses/index');
    }

    public function publish($lang, Course $item, Request $request)
    {
        if ($item->status == 1 or $item->status == 2 or $item->status == 3) {
            $user = $item->user()->first();
            switch ($request->input('action')) {
                case 'reject':
                    $item->status = 2;
                    $item->save();

                    $data = [
                        'message_text' => $request->rejectMessage,
                    ];


                    Mail::send('app.pages.page.emails.course_reject', ['data' => $data], function ($message) use ($request, $item, $user) {
                        $message->from(env("MAIL_USERNAME"), 'Enbek');
                        $message->to($user->email, 'Receiver')->subject('');
                    });


                    return redirect()->back()->with('status', trans('admin.pages.courses.course_reject', ['course_name' => $item->name, 'rejectMessage' => $request->rejectMessage]));
                    break;

            }

            $item->status = 3;
            $item->save();


            return redirect()->back()->with('status', trans('admin.pages.courses.course_published', ['course_name' => $item->name]));
        }
        return redirect('/' . $lang . '/admin/courses/index');
    }

    public function unpublish($lang, Course $item)
    {
        if ($item->status == 1 or $item->status == 2 or $item->status == 3) {
            $item->status = 2;
            $item->save();

            return redirect()->back()->with('status', trans('admin.pages.courses.course_unpublished', ['course_name' => $item->name]));
        }
        return redirect('/' . $lang . '/admin/courses/index');
    }

    public function quota_request($lang, Course $item)
    {
        if ($item->status == 1 or $item->status == 2 or $item->status == 3) {
            $author_course = $item->user()->first()->id;

            if ($item->quota_status != 1 and $item->cost > 0) {
                $notification = new Notification;
                $notification->name = "notifications.quota_request_description";
                $notification->course_id = $item->id;
                $notification->type = 1;
                $notification->save();

                $item->quota_status = 1;
                $item->save();

                $notification->users()->sync([$author_course]);

                $data = [
                    'email' => $item->user()->first()->email,
                    'description' => 'notifications.quota_request_description',
                    'course_id' => $item->id,
                    'course_name' => $item->name,
                    'user_id' => $item->user()->first()->id
                ];

//                Mail::send('app.pages.page.emails.quota_confirm', ['data' => $data], function ($message) use ($item) {
//                    $message->from(env("MAIL_USERNAME"), 'Enbek');
//                    $message->to($item->user()->first()->email, 'Receiver')->subject('');
//                });
                Mail::to($item->user()->first()->email)->send(new QuotaMessage($data));
            }

            $logger = new Log;
            $logger->log("quota_change", "courses", $item->id, 'Уведомление о публикации курса по квоте было отправлено автору курса');

            return redirect()->back()->with('status', __('admin.pages.courses.course_quote_request'));
        }
        return redirect('/' . $lang . '/admin/courses/index');
    }

    public function quota_contract($lang, Course $item, Request $request)
    {
        $item->quota_contract_number = $request->quota_contract_number;
        $item->save();

        return redirect()->back()->with('status', __('admin.pages.courses.quote_contract_saved'));
    }

    public function viewCourse($lang, Course $item)
    {
        $course_author = User::whereId($item->author_id)->first();

        $courses = $course_author->courses()->get();
        // Все оценки всех курсов
        $rates = [];
        foreach ($courses as $course) {
            foreach ($course->rate as $rate) {
                array_push($rates, $rate->rate);
            }
        }
        // Все ученики автора
        $author_students = [];
        foreach ($courses->unique('student_id') as $course) {
            foreach ($course->course_members as $member) {
                array_push($author_students, $member);
            }
        }
        // Все ученики закончившие курс
        $author_students_finished = [];
        foreach ($courses as $course) {
            foreach ($course->course_members->where('is_finished', '=', true) as $member) {
                array_push($author_students_finished, $member);
            }
        }
        // Оценка автора исходя из всех оценок
        if (count($rates) == 0) {
            $average_rates = 0;
        } else {
            $average_rates = array_sum($rates) / count($rates);
        }

        $themes = $item->themes()->orderBy('index_number', 'asc')->get();
        $lessons_count = count(Lesson::where('course_id', '=', $item->id)->get());

        $lessons = $item->lessons;
        $videos_count = [];
        $audios_count = [];
        $attachments_count = [];

        foreach ($lessons as $lesson) {
            if ($lesson->lesson_attachment != null) {
                if ($lesson->lesson_attachment->videos != null) {
                    $videos_count[] = count(json_decode($lesson->lesson_attachment->videos));
                }
                if ($lesson->lesson_attachment->audios != null) {
                    $audios_count[] = count(json_decode($lesson->lesson_attachment->audios));
                }
                if ($lesson->lesson_attachment->another_files != null) {
                    $attachments_count[] = count(json_decode($lesson->lesson_attachment->another_files));
                }
            }

        }


        return view('admin.v2.pages.courses.course_frame', [
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
            "attachments_count" => array_sum($attachments_count),
        ]);

    }

    public static function convertMunitesToTime(Int $minutes){
        //Конверт числа во время
        $format = '%02d:%02d';

        $hours = floor($minutes / 60);
        $minutes = ($minutes % 60);

        $time = sprintf($format, $hours, $minutes);

        return $time;
    }
}
