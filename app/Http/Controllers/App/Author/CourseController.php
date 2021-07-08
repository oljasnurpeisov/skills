<?php

namespace App\Http\Controllers\App\Author;

use App\Exports\ReportingExport;
use App\Extensions\CalculateQuotaCost;
use App\Http\Controllers\Controller;
use App\Libraries\Kalkan\Certificate;
use App\Models\Contract;
use App\Models\Course;
use App\Models\CourseAttachments;
use App\Models\Lesson;
use App\Models\Notification;
use App\Models\ProfessionalArea;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\StudentCourse;
use App\Models\Theme;
use App\Models\User;
use App\Services\Signing\ValidationService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\Exception\Exception;
use Services\Contracts\ContractService;
use Services\Course\AuthorCourseService;
use Services\Course\CourseService;


class CourseController extends Controller
{
    /**
     * @var CourseService
     */
    private $courseService;

    /**
     * @var AuthorCourseService
     */
    private $authorCourseService;

    /**
     * @var ContractService
     */
    private $contractService;

    /**
     * @var ValidationService
     */
    private $validationService;

    /**
     * CourseController constructor.
     *
     * @param CourseService $courseService
     * @param AuthorCourseService $authorCourseService
     * @param ContractService $contractService
     * @param
     */
    public function __construct(
        CourseService $courseService,
        AuthorCourseService $authorCourseService,
        ContractService $contractService,
        ValidationService  $validationService
    )
    {
        $this->courseService        = $courseService;
        $this->authorCourseService  = $authorCourseService;
        $this->contractService      = $contractService;
        $this->validationService    = $validationService;
    }

    public function createCourse($lang)
    {
        $skills = Skill::orderBy('name_' . $lang, 'asc')->get();
        return view("app.pages.author.courses.create_course", [
            "items" => [],
            "skills" => $skills
        ]);
    }

    /**
     * Сохранение курса
     *
     * @param Request $request
     * @return RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeCourse(Request $request)
    {
        $request->cost = str_replace(' ','', $request->cost);

        if ($request->is_paid and $request->cost > 0) {
            if ((Auth::user()->payment_info->merchant_login != null) and (Auth::user()->payment_info->merchant_password != null)) {

//                return $request->professions[0][0];
                $item = new Course;
                $item->name = $request->name;
                $item->author_id = Auth::user()->id;
                $item->lang = $request->lang;
                if ($request->is_paid === "true") {
                    $item->is_paid = 1;

                    if ($request->quota_status === "true") {
                        $item->quota_status = 4;
                    }
                } else {
                    $item->is_paid = 0;
                    $item->quota_status = 0;
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
                if ($request->is_poor_hearing) {
                    $item->is_poor_hearing = 1;
                } else {
                    $item->is_poor_hearing = 0;
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

                // Ссылки на видео курса
                $item_attachments->videos_link = json_encode($request->videos_link);

                // Ссылки на видео курса для слабовидящих
                if ($request->videos_poor_vision_link) {
                    $item_attachments->videos_poor_vision_link = json_encode($request->videos_poor_vision_link);
                }

                // Ссылки на видео курса для лиц с нарушениями слуха
                if ($request->videos_poor_hearing_link) {
                    $item_attachments->videos_poor_hearing_link = json_encode($request->videos_poor_hearing_link);
                }

                // Видео с устройства
                if (($request->videos != $item_attachments->videos)) {
                    File::delete(public_path($item_attachments->videos));

                    $item_attachments->videos = $request->videos;
                }
                // Видео с устройства для слабовидящих
                if (($request->videos_poor_vision != $item_attachments->videos_poor_vision)) {
                    File::delete(public_path($item_attachments->videos_poor_vision));

                    $item_attachments->videos_poor_vision = $request->videos_poor_vision;
                }
                // Видео с устройства для лиц с нарушениями слуха
                if (($request->videos_poor_hearing != $item_attachments->videos_poor_hearing)) {
                    File::delete(public_path($item_attachments->videos_poor_hearing));

                    $item_attachments->videos_poor_hearing = $request->videos_poor_hearing;
                }
                // Аудио с устройства
                if (($request->audios != $item_attachments->audios)) {
                    File::delete(public_path($item_attachments->audios));

                    $item_attachments->audios = $request->audios;
                }
                // Аудио с устройства для слабовидящих
                if (($request->audios_poor_vision != $item_attachments->audios_poor_vision)) {
                    File::delete(public_path($item_attachments->audios_poor_vision));

                    $item_attachments->audios_poor_vision = $request->audios_poor_vision;
                }
                // Аудио с устройства для лиц с нарушениями слуха
                if (($request->audios_poor_hearing != $item_attachments->audios_poor_hearing)) {
                    File::delete(public_path($item_attachments->audios_poor_hearing));

                    $item_attachments->audios_poor_hearing = $request->audios_poor_hearing;
                }

                $item_attachments->save();

                $this->courseService->saveSkillsTree($item, $request->all());
//                foreach ($request->skills as $key => $skill) {
//                    $item->skills()->attach([$skill => ['profession_id' => $request->professions, 'professional_area_id' => $request->professional_areas]]);
//                }

                return redirect("/" . app()->getLocale() . "/my-courses/drafts")->with('status', __('default.pages.courses.create_request_message'));
            }
            return redirect()->back()->withInput()->with('failed', __('default.pages.profile.pay_info_error', ['lang' => app()->getLocale()]));
        } else {
            $item = new Course;
            $item->name = $request->name;
            $item->author_id = Auth::user()->id;
            $item->lang = $request->lang;
            if ($request->is_paid === "true") {
                $item->is_paid = 1;

                if ($request->quota_status === "true") {
                    $item->quota_status = 4;
                }
            } else {
                $item->is_paid = 0;
                $item->quota_status = 0;
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
            if ($request->is_poor_hearing) {
                $item->is_poor_hearing = 1;
            } else {
                $item->is_poor_hearing = 0;
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

            // Ссылки на видео курса
            $item_attachments->videos_link = json_encode($request->videos_link);

            // Ссылки на видео курса для слабовидящих
            if ($request->videos_poor_vision_link) {
                $item_attachments->videos_poor_vision_link = json_encode($request->videos_poor_vision_link);
            }

            // Ссылки на видео курса для лиц с нарушениями слуха
            if ($request->videos_poor_hearing_link) {
                $item_attachments->videos_poor_hearing_link = json_encode($request->videos_poor_hearing_link);
            }

            // Видео с устройства
            if (($request->videos != $item_attachments->videos)) {
                File::delete(public_path($item_attachments->videos));

                $item_attachments->videos = $request->videos;
            }
            // Видео с устройства для слабовидящих
            if (($request->videos_poor_vision != $item_attachments->videos_poor_vision)) {
                File::delete(public_path($item_attachments->videos_poor_vision));

                $item_attachments->videos_poor_vision = $request->videos_poor_vision;
            }
            // Видео с устройства для лиц с нарушениями слуха
            if (($request->videos_poor_hearing != $item_attachments->videos_poor_hearing)) {
                File::delete(public_path($item_attachments->videos_poor_hearing));

                $item_attachments->videos_poor_hearing = $request->videos_poor_hearing;
            }
            // Аудио с устройства
            if (($request->audios != $item_attachments->audios)) {
                File::delete(public_path($item_attachments->audios));

                $item_attachments->audios = $request->audios;
            }
            // Аудио с устройства для слабовидящих
            if (($request->audios_poor_vision != $item_attachments->audios_poor_vision)) {
                File::delete(public_path($item_attachments->audios_poor_vision));

                $item_attachments->audios_poor_vision = $request->audios_poor_vision;
            }
            // Аудио с устройства для лиц с нарушениями слуха
            if (($request->audios_poor_hearing != $item_attachments->audios_poor_hearing)) {
                File::delete(public_path($item_attachments->audios_poor_hearing));

                $item_attachments->audios_poor_hearing = $request->audios_poor_hearing;
            }

            $item_attachments->save();

            $this->courseService->saveSkillsTree($item, $request->all());

//            foreach ($request->skills as $key => $skill) {
//                $item->skills()->attach([$skill => ['profession_id' => $request->professions, 'professional_area_id' => $request->professional_areas]]);
//            }

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
                $query = Auth::user()->courses()->where(function ($q) {return $q->where('status', '=', Course::onCheck)->orWhere('status', '=', 5);})->doesntHave('contracts');
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
            case('signing'):
                $query = Auth::user()->courses()->signingThisAuthor();
                $page_name = 'default.pages.courses.my_courses_signing';
                break;
        }

        $lang_ru = $request->lang_ru ?? null;
        $lang_kk = $request->lang_kk ?? null;
        $course_type = $request->course_type ?? '';
        $course_sort = $request->course_sort ?? '';
        $min_rating = $request->min_rating ?? 0;
        $members_count = $request->members_count ?? 0;
        $specialities = $request->specialities;
        $professional_areas = $request->professional_areas;
        $skills = $request->skills;

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
            })->withCount(['course_members' => function ($q) {
                $q->whereIn('paid_status', [1, 2]);
            }])->having('course_members_count', '>=', $members_count);
        }
        // Получить проф.области
        if ($professional_areas) {
            $professional_areas = ProfessionalArea::whereIn('id', $professional_areas)->get();

            $query->whereHas('professional_areas', function ($q) use ($request) {
                $q->whereIn('professional_areas.id', $request->professional_areas);
            });
        }
        // Получить профессии
        if ($specialities) {
            $professions = Professions::whereIn('id', $specialities)->get();

            $query->whereHas('professions', function ($q) use ($request) {
                $q->whereIn('professions.id', $request->specialities);
            });
        }
        // Сортировка по навыкам
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
            "skills" => $skills ?? null,
            "professional_areas" => $professional_areas ?? null
        ]);
    }

    public function courseShow($lang, Course $item)
    {
        $courses = Auth::user()->courses()->get();

        $courses->load('rate');
        $courses->load('course_members');

        // Все оценки всех курсов
        $rates = [];
        foreach ($courses as $course) {
            foreach ($course->rate as $rate) {
                array_push($rates, $rate->rate);
            }
        }
        // Все ученики автора
        $author_students = [];
        foreach ($courses as $course) {
            foreach ($course->course_members as $member) {
                $author_students[$member['student_id']][] = $member;
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

        if ($item->author_id == Auth::user()->id) {
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

            $untheme_lessons = Lesson::whereCourseId($item->id)
                ->whereThemeId(null)
                ->whereNotIn('type', [3, 4])
                ->orderBy('index_number', 'asc')
                ->get();

            foreach ($themes as $theme) {
                $theme->item_type = 'theme';
            }

            foreach ($untheme_lessons as $unthemes_lesson) {
                $unthemes_lesson->item_type = 'lesson';
            }

            $course_data_items = $themes->merge($untheme_lessons)->sortBy('index_number')->values();

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
                "attachments_count" => array_sum($attachments_count),
                'course_data_items' => $course_data_items,
                'quota_cost' => CalculateQuotaCost::calculate_quota_cost($item)
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
                    $skills = Skill::get();
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
        $request->cost = str_replace(' ','', $request->cost);

        $item->name = $request->name;
        $item->lang = $request->lang;
        if ($request->is_paid === "true") {
            $item->is_paid = 1;

            if ($request->quota_status === "true") {
                $item->quota_status = 4;
            }
        } else {
            $item->is_paid = 0;
            $item->quota_status = 0;
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
        if ($request->is_poor_hearing) {
            $item->is_poor_hearing = 1;
        } else {
            $item->is_poor_hearing = 0;
        }
        $item->cost = $request->cost;
        $item->profit_desc = $request->profit_desc;
        $item->teaser = $request->teaser;
        $item->description = $request->description;

        $item->certificate_id = $request->certificate_id;

        if (($request->image != $item->image)) {
            File::delete(public_path($item->image));

            $item->image = $request->image;
        }
        $this->courseService->updateSkillsTree($item, $request->all());
//        $item->skills()->detach();
//        foreach ($request->skills as $key => $skill) {
//            $item->skills()->attach([$skill => ['profession_id' => $request->professions, 'professional_area_id' => $request->professional_areas]]);
//        }
        $item->save();

        $item_attachments = CourseAttachments::where('course_id', '=', $item->id)->first();

        // Ссылки на видео курса
        $item_attachments->videos_link = $request->videos_link;
        // Ссылки на видео курса для слабовидящих
        if ($request->videos_poor_vision_link) {
            $item_attachments->videos_poor_vision_link = json_encode($request->videos_poor_vision_link);
        }
        // Ссылки на видео курса для лиц с нарушениями слуха
        if ($request->videos_poor_hearing_link) {
            $item_attachments->videos_poor_hearing_link = json_encode($request->videos_poor_hearing_link);
        }

        $videos = array_merge(json_decode($request->localVideo) ?? [], $request->localVideoStored ?? []);
        $audios = array_merge(json_decode($request->localAudio) ?? [], $request->localAudioStored ?? []);

        $videos_poor_vision = array_merge(json_decode($request->localVideo1) ?? [], $request->localVideoStored1 ?? []);
        $audios_poor_vision = array_merge(json_decode($request->localAudio1) ?? [], $request->localAudioStored1 ?? []);

        $videos_poor_hearing = array_merge(json_decode($request->localVideo2) ?? [], $request->localVideoStored2 ?? []);
        $audios_poor_hearing = array_merge(json_decode($request->localAudio2) ?? [], $request->localAudioStored2 ?? []);

        // Видео с устройства
        if ($videos != $item_attachments->videos) {

            $item_attachments->videos = $videos;

            $item_attachments->save();
        }
        // Аудио с устройства
        if ($audios != $item_attachments->audios) {

            $item_attachments->audios = $audios;

            $item_attachments->save();
        }
        // Видео с устройства (для слабовидящих)
        if ($videos_poor_vision != $item_attachments->videos_poor_vision) {

            $item_attachments->videos_poor_vision = $videos_poor_vision;

            $item_attachments->save();
        }
        // Аудио с устройства (для слабовидящих)
        if ($audios_poor_vision != $item_attachments->audios_poor_vision) {

            $item_attachments->audios_poor_vision = $audios_poor_vision;

            $item_attachments->save();
        }
        // Видео с устройства для лиц с нарушениями слуха
        if ($videos_poor_hearing != $item_attachments->videos_poor_hearing) {
            $item_attachments->videos_poor_hearing = $videos_poor_hearing;

            $item_attachments->save();
        }
        // Аудио с устройства для лиц с нарушениями слуха
        if ($audios_poor_hearing != $item_attachments->audios_poor_hearing) {

            $item_attachments->audios_poor_hearing = $audios_poor_hearing;

            $item_attachments->save();
        }

        $item_attachments->save();


        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $item->id)->with('status', __('default.pages.profile.save_success_message'));
    }

    public function publishCourse($lang, Course $item)
    {
        $testworks = $item->lessons->whereIn('type', [3, 4])->first();
        if (!empty($testworks)) {
            $item->status = 1;
            $item->save();

            $recipients = User::whereHas('roles', function ($q) {
                $q->where('slug', '=', 'moderator');
            })->pluck('email')->toArray();

            $data = [
                'course_id' => $item->id,
            ];

            Mail::send('app.pages.page.emails.new_verification_course', ['data' => $data], function ($message) use ($item, $recipients) {
                $message->from(env("MAIL_USERNAME"), 'Enbek');
                $message->to($recipients, 'Receiver')->subject(__('notifications.course_verification_title'));
            });

            return redirect("/" . app()->getLocale() . "/my-courses/on-check")->with('status', __('default.pages.courses.publish_request_message'));
        } else {
            return redirect()->back()->with('error', __('default.pages.courses.publish_failed_message'));
        }
    }

    public function deleteCourse($lang, Course $item)
    {
        if ($item->author_id == Auth::user()->id) {
            $item->previous_status = $item->status;
            $item->status = 4;
            $item->save();

            // удаляем активные договора
            $this->contractService->removeActiveContracts($item->id);

            return redirect("/" . app()->getLocale() . "/my-courses")->with('status', __('default.pages.courses.delete_request_message'));

        }
        return redirect("/" . app()->getLocale() . "/my-courses");
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
            $item->quota_status = 4;
            $item->save();

            $notification = new Notification;
            $notification->name = 'notifications.confirm_quota_description';
            $notification->course_id = $item->id;
            $notification->type = 0;
            $notification->save();

            $notification_1 = new Notification;
            $notification_1->name = 'notifications.course_quota_wait_contract';
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
        if ($item->previous_status === Course::draft) {
            $item->status = Course::draft;
        } else {
            $item->status = Course::onCheck;
        }

        $item->save();

        if ($item->status == Course::draft) {
            return redirect("/" . app()->getLocale() . "/my-courses/drafts")->with('status', __('default.pages.courses.reestablish_request_message'));
        } else {
            return redirect("/" . app()->getLocale() . "/my-courses")->with('status', __('default.pages.courses.publish_request_message'));
        }
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
        foreach ($items as $course) {
            foreach ($course->course_members as $member) {
                $author_students[$member['student_id']][] = $member;
            }
        }

        // Цена купленных курсов
        $all_cost_courses = [];
        $quota_cost_courses = [];
        foreach ($items as $course) {
            $all_cost_courses[] = $course->course_members->whereIn('paid_status', [1, 2])->sum('payment.amount');
            $quota_cost_courses[] = $course->course_members->where('paid_status', '=', 2)->sum('payment.amount');
        }

        $query = Auth::user()->courses()->where('status', '=', Course::published);
        // Сортировка Рейтинг - по возрастанию
        if ($request->sort_by == 'sort_by_rate_low') {
            $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                ->select('course_rate.rate as course_rate', 'courses.*')
                ->orderBy('course_rate.rate', 'asc')->groupBy('course_id');
            // Сортировка Рейтинг - по убыванию
        } else if ($request->sort_by == 'sort_by_rate_high') {
            $query->leftJoin('course_rate', 'courses.id', '=', 'course_rate.course_id')
                ->select('course_rate.rate as course_rate', 'courses.*')
                ->orderBy('course_rate.rate', 'desc')->groupBy('course_id');
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

    public function statisticForChart(Request $request)
    {
        $data = [
            'title1' => "Общий заработок",
            'title2' => "Заработано по квотам",
            'color1' => '#00C608',
            'color2' => '#F2C94C',
            'data' => [],
        ];

        $dFrom = $request->get("date_from", Carbon::now()->subDays(90)->format('Y-m-d'));
        $dTo = $request->get("date_to", Carbon::now()->format('Y-m-d'));

        if ($dFrom === null && $dTo === null) {
            $dFrom = Carbon::now()->subDays(90);
            $dTo = Carbon::now();
        } elseif ($dFrom === null) {
            $time = strtotime($dTo);
            $dFrom = Carbon::createFromTimestamp($time)->subDays(90);
            $dTo = Carbon::createFromTimestamp($time);
        } elseif ($dTo === null) {
            $dFrom = strtotime($dFrom);
            $dFrom = Carbon::createFromTimestamp($dFrom);
            $dTo = Carbon::now();
        } else {
            $dFrom = strtotime($dFrom);
            $dFrom = Carbon::createFromTimestamp($dFrom);
            $dTo = strtotime($dTo);
            $dTo = Carbon::createFromTimestamp($dTo);
        }

        $items = StudentCourse::whereHas('course', function ($q) {
            $q->where('courses.author_id', '=', Auth::user()->id);
        })
            ->whereBetween('created_at', [$dFrom, $dTo->endOfDay()])
            ->orderBy('created_at', 'asc')->where('paid_status', '!=', 0)
            ->with('course')
            ->get()
            ->groupBy(function ($val) {
                return Carbon::parse($val->created_at)->format('d');
            });

        while ($dFrom <= $dTo) {
            $data['data'][] = [
                'date' => $dFrom->format("Y-m-d"),
                'value1' => 0,
                'value2' => 0,
            ];
            $dFrom = $dFrom->addDay();
        }

        foreach ($items as $item) {
            $key = array_search($item->first()->created_at->format("Y-m-d"), array_column($data['data'], 'date'));
            $data['data'][$key] = [
                "date" => $item->first()->created_at,
                "value1" => $item->whereIn('paid_status', [1, 2])->sum('payment.amount'),
                "value2" => $item->where('paid_status', '=', 2)->sum('payment.amount')];
        }

        return response()->json($data);
    }

    public function statisticForChartDemo(Request $request)
    {
        $data = [
            'title1' => "Общий заработок",
            'title2' => "Заработано по квотам",
            'color1' => '#00C608',
            'color2' => '#F2C94C',
            'data' => [],
        ];

        $dFrom = $request->get("date_from", Carbon::now()->subDays(90)->format('Y-m-d'));
        $dTo = $request->get("date_to", Carbon::now()->format('Y-m-d'));

        if ($dFrom === null && $dTo === null) {
            $dFrom = Carbon::now()->subDays(90);
            $dTo = Carbon::now();
        } elseif ($dFrom === null) {
            $time = strtotime($dTo);
            $dFrom = Carbon::createFromTimestamp($time)->subDays(90);
            $dTo = Carbon::createFromTimestamp($time);
        } elseif ($dTo === null) {
            $dFrom = strtotime($dFrom);
            $dFrom = Carbon::createFromTimestamp($dFrom);
            $dTo = Carbon::now();
        } else {
            $dFrom = strtotime($dFrom);
            $dFrom = Carbon::createFromTimestamp($dFrom);
            $dTo = strtotime($dTo);
            $dTo = Carbon::createFromTimestamp($dTo);
        }

        while ($dFrom <= $dTo) {
            $data['data'][] = [
                'date' => $dFrom->format("Y-m-d\TH:i:s"),
                'value1' => rand(0, 100),
                'value2' => rand(0, 300),
            ];
            $dFrom = $dFrom->addDay();
        }

        return response()->json($data);
    }

    public function reportingCourse(Request $request)
    {

        $from = $request->date_from;
        $to = $request->date_to;
        $all_time = $request->all_time;

        $date_from = Carbon::parse($from ?? '01.01.2020')
            ->startOfDay()
            ->toDateTimeString();
        $date_to = Carbon::parse($to)
            ->endOfDay()
            ->toDateTimeString();

        $query = Course::where('author_id', '=', Auth::user()->id)
            // Рейтинг
            ->with(['rate' => function ($q) use ($date_from, $date_to) {
                $q->whereBetween('course_rate.created_at', [$date_from, $date_to]);
                // Записавшиеся
            }])->with(['course_members' => function ($q) use ($date_from, $date_to) {
                $q->whereBetween('student_course.created_at', [$date_from, $date_to]);
            }])->with(['quotaCost' => function ($q) use ($date_from, $date_to) {
                $q->whereBetween('course_quota_cost.created_at', [$date_from, $date_to]);
            }])->with(['course_members' => function ($q) use ($date_from, $date_to) {
                $q->whereBetween('student_course.created_at', [$date_from, $date_to]);
            }]);

        Session::put('export_reporting', $query->get());
        Session::put('export_reporting_dates', [$date_from, $date_to]);

        $items = $query->paginate(5);

        return view("app.pages.author.courses.reporting", [
            'items' => $items,
            'from' => $from,
            'to' => $to,
            'all_time' => $all_time,
            'date_from' => $date_from,
            'date_to' => $date_to,
        ]);
    }

    public function exportReporting(Request $request)
    {
        $query = Session::get('export_reporting');
        $dates = Session::get('export_reporting_dates');
        $export = [[]];
        $lang = app()->getLocale();
        foreach ($query as $i) {
            // Платный
            if ($i->is_paid == true) {
                $is_paid = __('default.pages.reporting.paid_course');
            } else {
                $is_paid = __('default.pages.reporting.free_course');
            }
            // Квота
            if ($i->quota_status == 2) {
                $is_quota = __('default.yes_title');
            } else {
                $is_quota = __('default.no_title');
            }
            // Наименование курса
            $name = $i->name;
            // Навыки
            $skills = implode(', ', array_filter($i->skills->pluck('name_' . $lang)->toArray())) ?: implode(', ', $i->skills->pluck('name_ru')->toArray());
            // Профессия
            if (count($i->professions()->pluck('name_ru')->toArray()) <= 0) {
                $professions = '-';
            } else {
                $professions = implode(', ', array_filter($i->professions()->pluck('name_' . $lang)->toArray())) ?: implode(', ', array_filter($i->professions()->pluck('name_ru')->toArray()));
            }
            // Проф.область
            if (count($i->professional_areas()->pluck('name_ru')->toArray()) <= 0) {
                $professional_areas = '-';
            } else {
                $professional_areas = implode(', ', array_filter($i->professional_areas()->pluck('name_' . $lang)->toArray())) ?: implode(', ', array_filter($i->professional_areas()->pluck('name_ru')->toArray()));
            }
            // Рейтинг курса
            $course_rate = $i->rate->pluck('rate')->avg() ?? 0;
            // Статус курса
            $course_status = __('default.pages.reporting.statuses.' . $i->status);
            // Стоимость курса
            $course_cost = $i->cost ?? '-';
            // Участников курса
            if ($i->quota_status == 2 and $i->is_paid == true) {
                $course_members_count = count($i->course_members->where('paid_status', '=', 1)) . "/" . count($i->course_members->where('paid_status', '=', 2));
            } else {
                $course_members_count = count($i->course_members->whereIn('paid_status', [1, 2]));
            }
            // Получили сертификат
            $got_certificate_members_count = count($i->course_members->where('is_finished', '=', true));
            if ($i->courseWork()) {
                $confirmed_qualifications = $i->courseWork()->finishedLesson()->whereBetween('updated_at', $dates)->count();
            } else {
                $confirmed_qualifications = '-';
            }
            // Тип курса
            $course_type = $i->is_paid == true ? __('default.pages.reporting.paid_course') : __('default.pages.reporting.free_course');
            // Стоимость по квоте
            $quota_cost = $i->quotaCost->last()->cost ?? '-';
            // Записано обучающихся
            $members_free = $i->course_members->where('paid_status', '=', 3)->count();
            $members_paid = $i->course_members->where('paid_status', '=', 1)->count();
            $members_quota = $i->course_members->where('paid_status', '=', 2)->count();
            // Подтвердили квалификацию
            $qualificated_free = $i->course_members->where('paid_status', '=', 3)->where('is_qualificated', '=', true)->count();
            $qualificated_paid = $i->course_members->where('paid_status', '=', 1)->where('is_qualificated', '=', true)->count();
            $qualificated_quota = $i->course_members->where('paid_status', '=', 2)->where('is_qualificated', '=', true)->count();
            // Получили сертификат
            $certificate_free = $i->course_members->where('paid_status', '=', 3)->where('is_finished', '=', true)->count();
            $certificate_paid = $i->course_members->where('paid_status', '=', 1)->where('is_finished', '=', true)->count();
            $certificate_quota = $i->course_members->where('paid_status', '=', 2)->where('is_finished', '=', true)->count();
            // Итого получено за курсы
            $total_get_paid = $i->course_members->where('paid_status', '=', 1)->sum('payment.amount');
            $total_get_quota = $i->course_members->where('paid_status', '=', 2)->sum('payment.amount');

            $newElement = ['name' => $name, 'professional_areas' => $professional_areas, 'professions' => $professions, 'skills' => $skills, 'course_rate' => $course_rate,
                'course_status' => $course_status, 'course_type' => $course_type, 'course_cost' => $course_cost, 'is_quota' => $is_quota, 'quota_cost' => $quota_cost,
                'members_free' => $members_free, 'certificate_free' => $certificate_free, 'members_paid' => $members_paid,
                'certificate_paid' => $certificate_paid, 'total_get_paid' => $total_get_paid, 'members_quota' => $members_quota,
                'certificate_quota' => $certificate_quota, 'total_get_quota' => $total_get_quota];

            array_push($export, $newElement);
        }

        asort($export);
        return Excel::download(new ReportingExport($export), '' . __('default.pages.courses.report_title') . '.xlsx');
    }

    public function getCourseData($lang, Course $course)
    {
        if ($course->author_id == Auth::user()->id) {
            $themes = Theme::where('course_id', '=', $course->id)->with('lessons')->get();
            $unthemes_lessons = Lesson::where('theme_id', '=', null)
                ->where('course_id', '=', $course->id)
                ->whereNotIn('type', [3, 4])
                ->get();

            foreach ($themes as $theme) {
                $theme->order = $theme->index_number;
                $theme->item_type = 'theme';

                foreach ($theme->lessons as $lesson) {
                    $lesson->order = $lesson->index_number;

                    if ($lesson->type != 1) {
                        $lesson->type = $lesson->lesson_type->getAttribute('name_' . $lang) ?? $lesson->lesson_type->getAttribute('name_ru');
                        $lesson->type .= $lesson->end_lesson_type == 0 ? ' (' . __('default.pages.lessons.test_title') . ')' : ' (' . __('default.pages.lessons.homework_title') . ')';
                    } else {
                        $lesson->type = $lesson->lesson_type->getAttribute('name_' . $lang) ?? $lesson->lesson_type->getAttribute('name_ru');
                    }

                }
            }

            foreach ($unthemes_lessons as $unthemes_lesson) {

                $unthemes_lesson->order = $unthemes_lesson->index_number;
                $unthemes_lesson->item_type = 'lesson';

                $unthemes_lesson->lessons = [];

                if ($unthemes_lesson->type != 1) {
                    $unthemes_lesson->type = $unthemes_lesson->lesson_type->getAttribute('name_' . $lang) ?? $unthemes_lesson->lesson_type->getAttribute('name_ru');
                    $unthemes_lesson->type .= $unthemes_lesson->end_lesson_type == 0 ? ' (' . __('default.pages.lessons.test_title') . ')' : ' (' . __('default.pages.lessons.homework_title') . ')';
                } else {
                    $unthemes_lesson->type = $unthemes_lesson->lesson_type->getAttribute('name_' . $lang) ?? $unthemes_lesson->lesson_type->getAttribute('name_ru');
                }
            }

            $themes = $themes->merge($unthemes_lessons);

            return $themes;
        }
        return abort(404);
    }

    /**
     * Предпросмотр договора
     *
     * @param Request $request
     * @return View
     */
    public function contract(Request $request)
    {
        return view('app.pages.author.courses.signing', [
            'contract' => Contract::pending()->whereHas('course', function ($q) {
                return $q->whereAuthorId(Auth::user()->id);
            })->findOrFail($request->contract_id)
        ]);
    }

    /**
     * Договор
     *
     * @param Request $request
     * @return View
     * @throws Exception
     */
    public function contractDoc(Request $request): View
    {
        $contract = Contract::
            pending()
            ->whereHas('current_route', function ($q) {
                return $q->whereRoleId(Auth::user()->role->role_id);
            })
            ->whereHas('course', function ($q) {
                return $q->whereAuthorId(Auth::user()->id);
            })
            ->findOrFail($request->contract_id);

        return view('app.pages.author.courses.contractDoc', [
            'contract' => $this->contractService->contractToHtml($contract->id)
        ]);
    }

    /**
     * Отклонение договора автором
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function contractReject(Request $request): RedirectResponse
    {
        $this->authorCourseService->rejectContract($request->contract_id);

        return redirect(route('author.courses.my_courses', ['lang' => $request->lang]));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function xml(Request $request): JsonResponse
    {
        $xml = $this->authorCourseService->generateXml($request->contract_id);

        return response()->json(['xml' => $xml]);
    }

    /**
     * Check and send contract (course)
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function next(Request $request)
    {
        if(!empty($_POST)) {

            $xml = $request->post('xml');

            $success = false;
            $certificate = null;

            $x509 = Certificate::getCertificate($xml, true);

            if ($x509) {
                $certificate = $x509;
            }

            if($certificate->canSign(Auth::user()->iin) && $this->validationService->verifyXml($xml)) {

                $success = true;

                $message = 'Договор успешно подписан';

                $this->authorCourseService->acceptContract($request->contract_id, $xml, $this->validationService->getResponse());

                Session::flash('status', $message);

            } else {
                $message = $certificate->getError() ?: $this->validationService->getError();
            }

            return response()->json([
                'success' => $success,
                'message' => $message,
                'certificate' => $certificate,
                'redirect' => route('author.courses.signing', ['lang' => $request->lang]),
                'response' => $this->validationService->getResponse()
            ], $success ? 200 : 500);

        } else {

            $message = 'Договор успешно подписан';
            $this->authorCourseService->acceptContract($request->contract_id);
            Session::flash('status', $message);

            return redirect(route('author.courses.signing', ['lang' => $request->lang]));
        }
    }
}
