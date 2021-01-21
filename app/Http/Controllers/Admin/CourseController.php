<?php

namespace App\Http\Controllers\Admin;

//use App\Helpers\Buffet;
//use App\Models\Card;
//use App\Models\Company;
use App\Extensions\FormatDate;
use App\Extensions\NotificationsHelper;
use App\Extensions\RandomStringGenerator;
use App\Mail\QuotaMessage;
use App\Models\Course;
use App\Models\CourseAttachments;
use App\Models\Lesson;
use App\Models\LessonAttachments;
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

        $query = Course::orderBy('id', 'desc');
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
        return view('admin.v2.pages.courses.view', [
            'item' => $item
        ]);
    }

    public function publish($lang, Course $item, Request $request)
    {
        if ($item->status == 1 or $item->status == 2 or $item->status == 3) {
            $user = $item->user()->first();
            switch ($request->input('action')) {
                case 'reject':
                    $item->status = 2;
                    $item->save();

                    $notification_data = [
                        'course_reject_message' => $request->rejectMessage
                    ];
                    $notification_name = "notifications.course_reject";
                    NotificationsHelper::createNotification($notification_name, $item->id, $user->id, 0, $notification_data);

                    $data = [
                        'item' => $item,
                        'lang' => $lang,
                        'message_text' => $request->rejectMessage,
                    ];

                    Mail::send('app.pages.page.emails.course_reject', ['data' => $data], function ($message) use ($request, $item, $user) {
                        $message->from(env("MAIL_USERNAME"), env('APP_NAME'));
                        $message->to($user->email, 'Receiver')->subject(__('notifications.publish_course_title'));
                    });


                    return redirect()->back()->with('status', trans('admin.pages.courses.course_reject', ['course_name' => $item->name, 'rejectMessage' => $request->rejectMessage]));
                    break;

            }

            $item->status = 3;
            $item->quota_cost = $this->calculate_quota_cost($item);
            $item->save();

            $notification_name = "notifications.course_publish";
            NotificationsHelper::createNotification($notification_name, $item->id, $user->id);

            $data = [
                'item' => $item,
                'lang' => $lang,
            ];

            Mail::send('app.pages.page.emails.course_confirm', ['data' => $data], function ($message) use ($request, $item, $user) {
                $message->from(env("MAIL_USERNAME"), env('APP_NAME'));
                $message->to($user->email, 'Receiver')->subject(__('notifications.publish_course_title'));
            });

            return redirect()->back()->with('status', trans('admin.pages.courses.course_published', ['course_name' => $item->name]));
        }
        return redirect('/' . $lang . '/admin/courses/index');
    }

    public function unpublish($lang, Course $item)
    {
        if ($item->status == 1 or $item->status == 2 or $item->status == 3) {
            $item->status = 4;
            $item->save();

            return redirect()->back()->with('status', trans('admin.pages.courses.course_unpublished', ['course_name' => $item->name]));
        }
        return redirect('/' . $lang . '/admin/courses/index');
    }

    public function calculate_quota_cost(Course $course)
    {
        //
        $content_format_percent = 60;
        $lesson_tests_percent = 4;
        $final_test_percent = 6;
        $rate_percent = 10;
        $lang_percent = 10;
        $poor_vision_percent = 10;
        $coefficient_1 = 0;
        $coefficient_2 = 0.5;
        $coefficient_3 = 1;
        $hour_cost = 2001.05;

        $increase = 0;

        // Количество уроков
        $lessons_count = 0;
        // Вложения курса
        $attachments_forms_count = 0;
        $videos_forms_count = 0;
        $audios_forms_count = 0;
        $files_forms_count = 0;
        // Тесты, 0 - отсутствуют, 1 - разработаны к отдельным модулям, 2 - есть в каждом модуле
        $test_status = 0;
        // Количество тестов
        $tests_count = 0;
        // Рейтинг, 0 - менее 30 %, 1 - 31-50%, 2 - 51% и более
        $rate_status = 0;
        // Длительность курса
        $course_duration = 0;

        foreach ($course->lessons as $lesson) {
            // Посчитать количество уроков без учета курсовой и финального теста
            if ($lesson->type != 3 and $lesson->type != 4){
                $lessons_count++;
            }

            if ($lesson->lesson_attachment != null) {
                // Видео
                if ($lesson->lesson_attachment->videos != null) {
                    $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos));
                }
                if ($lesson->lesson_attachment->videos_poor_vision != null) {
                    $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos_poor_vision));
                }
                // Ссылки на видео
                if ($lesson->lesson_attachment->videos_link != null) {
                    if (json_decode($lesson->lesson_attachment->videos_link) != [null]) {
                        $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos_link));
                    }
                }
                if ($lesson->lesson_attachment->videos_poor_vision_link != null) {
                    if (json_decode($lesson->lesson_attachment->videos_poor_vision_link) != [null]) {
                        $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos_poor_vision_link));
                    }
                }
                // Аудио
                if ($lesson->lesson_attachment->audios != null) {
                    $audios_forms_count += count(json_decode($lesson->lesson_attachment->audios));
                }
                if ($lesson->lesson_attachment->audios_poor_vision != null) {
                    $audios_forms_count += count(json_decode($lesson->lesson_attachment->audios_poor_vision));
                }
                // Другие файлы
                if ($lesson->lesson_attachment->another_files != null) {
                    $files_forms_count += count(json_decode($lesson->lesson_attachment->another_files));
                }
                if ($lesson->lesson_attachment->another_files_poor_vision != null) {
                    $files_forms_count += count(json_decode($lesson->lesson_attachment->another_files_poor_vision));
                }
            }
            // Тесты
            if ($lesson->end_lesson_type == 0 and $lesson->type == 2){
                $tests_count++;
            }
            // Время урока
            if ($lesson->type != 3 and $lesson->type != 4) {
                $course_duration += $lesson->duration;
            }
        }
        // Финальный тест
        if ($course->finalTest() != null) {
            $questions_count = count(json_decode($course->finalTest()->practice)->questions);
        }else{
            $questions_count = 0;
        }
        // Посчитать количетсво вопросов в финальном тесте
        // Статус финального теста, 0 - отсутствует или меньше 20, 1 - 20-25 заданий, 25-35
        if ($questions_count >= 20 and $questions_count <= 25 and $questions_count != 0) {
            $final_test_status = 1;
        }else if ($questions_count >= 26){
            $final_test_status = 2;
        }else{
            $final_test_status = 0;
        }
        // Посчитать количество форм
        if ($videos_forms_count > 0) {
            $attachments_forms_count++;
        }
        if ($audios_forms_count > 0) {
            $attachments_forms_count++;
        }
        if ($files_forms_count > 0) {
            $attachments_forms_count++;
        }
        // Посчитать промежуточные тесты
        if ($tests_count < $lessons_count and $tests_count != 0){
            $test_status = 1;
        } else if ($tests_count == $lessons_count) {
            $test_status = 2;
        } else if ($attachments_forms_count == 0) {
            $test_status = 0;
        }
        // Посчитать оценки по курсу
        $course_rate_avg = $course->rate->avg('rate') ?? 0;
        $course_rate_avg_percent = ($course_rate_avg * 100) / 5;
        if ($course_rate_avg_percent < 30){
            $rate_status = 0;
        } else if ($course_rate_avg_percent >= 31 and $course_rate_avg_percent <= 50){
            $rate_status = 1;
        } else if ($course_rate_avg_percent >= 51) {
            $rate_status = 2;
        }
        // Посчитать надбавку
        // Подсчет надбавки по формам
        if ($attachments_forms_count <= 2 and $attachments_forms_count > 0 ){
            $increase += $content_format_percent * $coefficient_2;
        } else if ($attachments_forms_count >= 3) {
            $increase += $content_format_percent * $coefficient_3;
        } else if ($attachments_forms_count == 0) {
            $increase += $content_format_percent * $coefficient_1;
        }
        // Подсчет надбавки по тестам
        switch ($test_status){
            case 0:
                $increase += $final_test_percent * $coefficient_1;
                break;
            case 1:
                $increase += $final_test_percent * $coefficient_2;
                break;
            case 2:
                $increase += $final_test_percent * $coefficient_3;
        }
        // Подсчет надбавки по финальному тесту
        switch ($final_test_status){
            case 0:
                $increase += $lesson_tests_percent * $coefficient_1;
                break;
            case 1:
                $increase += $lesson_tests_percent * $coefficient_2;
                break;
            case 2:
                $increase += $lesson_tests_percent * $coefficient_3;
        }
        // Подсчет надбавки по рейтингу
        switch ($rate_status){
            case 0:
                $increase += $rate_percent * $coefficient_1;
                break;
            case 1:
                $increase += $rate_percent * $coefficient_2;
                break;
            case 2:
                $increase += $rate_percent * $coefficient_3;
        }
        // Подсчет надбавки по языку
        if ($course->lang == 0) {
            $increase += $lang_percent * $coefficient_3;
        }else{
            $increase += $lang_percent * $coefficient_1;
        }
        // Подсчет надбавки по доступной среде
        if ($course->is_poor_vision == true) {
            $increase += $poor_vision_percent * $coefficient_2;
        }else{
            $increase += $poor_vision_percent * $coefficient_1;
        }
        // Подсчет времени курса
        $course_duration = $course_duration / 60;
        // Подсчет стоимости курса
        $course_cost = $hour_cost * round($course_duration);
        $increase_cost = ($course_cost * $increase) / 100;
        $course_cost = $course_cost + $increase_cost;
        $course_cost_person = $course_cost/13;

        return round($course_cost_person);
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
                    'description' => 'notifications.quota_request_description_mail',
                    'course_id' => $item->id,
                    'course_name' => $item->name,
                    'user_id' => $item->user()->first()->id,
                    'lang' => $lang
                ];

                Mail::send('app.pages.page.emails.quota_confirm', ['data' => $data], function ($message) use ($item) {
                    $message->from(env("MAIL_USERNAME"), env('APP_NAME'));
                    $message->to($item->user()->first()->email, 'Receiver')->subject(__('notifications.publish_course_title'));
                });
            }

            $logger = new Log;
            $logger->log("quota_change", "courses", $item->id, 'Уведомление о публикации курса по квоте было отправлено автору курса');

            return redirect()->back()->with('status', __('admin.pages.courses.course_quote_request'));
        }
        return redirect('/' . $lang . '/admin/courses/index');
    }

    public function quota_contract($lang, Course $item, Request $request)
    {
        $item->quota_status = 2;
        $item->quota_contract_number = $request->quota_contract_number;
        $item->save();

        $notification = new Notification;
        $notification->name = 'notifications.course_quota_access';
        $notification->course_id = $item->id;
        $notification->type = 0;
        $notification->save();

        $notification->users()->sync([$item->author_id]);

        return redirect()->back()->with('status', __('admin.pages.courses.quote_contract_saved'));
    }


}
