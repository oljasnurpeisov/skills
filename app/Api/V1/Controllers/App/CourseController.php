<?php

namespace App\Api\V1\Controllers\App;

use App\Api\V1\Classes\Message;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Transformers\MessageTransformer;
use App\Extensions\FormatDate;
use App\Extensions\NotificationsHelper;
use App\Extensions\YoutubeParse;
use App\Models\Course;
use App\Models\CourseRate;
use App\Models\Lesson;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\StudentCertificate;
use App\Models\StudentCourse;
use App\Models\StudentLesson;
use App\Models\Theme;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class CourseController extends BaseController
{
    public function getAuthors(Request $request)
    {
        $hash = $request->header("hash");
        $lang = $request->header("lang", 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'hash' => 'required',
        ];
        $payload = [
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $items = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'author');
        })->paginate($this->per_page);

        $next_page_number = null;
        if ($items->nextPageUrl()) {
            $t = parse_url($items->nextPageUrl());
            $t = isset($t["query"]) ? $t["query"] : "";
            $t = explode("=", $t);
            $next_page_number = in_array("page", $t) ? $t[array_search("page", $t) + 1] : null;
        }

        $data = [
            "items" => [],
            "next" => $next_page_number,
        ];

        foreach ($items as $item) {
            $data["items"][] = [
                'id' => $item->id,
                'email' => $item->email,
                'name' => $item->author_info->name . " " . $item->author_info->surname,
                'avatar' => $item->author_info->avatar
            ];
        }

        $message = new Message(__('api/messages.authors.title'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function getProfessions(Request $request)
    {
        $term = $request->get('term');
        $hash = $request->header("hash");
        $lang = $request->header("lang", 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'hash' => 'required',
        ];
        $payload = [
            'term' => $term,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $items = Professions::where('name_' . $lang, 'like', '%' . $term . '%')
            ->where('parent_id', '!=', null)
            ->orderBy('name_' . $lang, 'asc')
            ->paginate($this->per_page);

        $next_page_number = null;
        if ($items->nextPageUrl()) {
            $t = parse_url($items->nextPageUrl());
            $t = isset($t["query"]) ? $t["query"] : "";
            $t = explode("=", $t);
            $next_page_number = in_array("page", $t) ? $t[array_search("page", $t) + 1] : null;
        }

        $data = [
            "items" => [],
            "next" => $next_page_number,
        ];

        foreach ($items as $item) {
            $data["items"][] = [
                'id' => $item->id,
                'name' => $item->getAttribute('name_' . $lang)
            ];
        }

        $message = new Message(__('api/messages.professions.title'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function getSkills(Request $request)
    {
        $term = $request->get('term');
        $professions = $request->get('professions');
        $hash = $request->header("hash");
        $lang = $request->header("lang", 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'hash' => 'required',
        ];
        $payload = [
            'professions' => $professions,
            'term' => $term,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($professions != []) {

            $professions_group = Professions::whereIn('id', json_decode($professions, true))->pluck('parent_id');
            $items = Skill::where('name_' . $lang, 'like', '%' . $term . '%')
                ->whereHas('group_professions', function ($q) use ($professions_group) {
                    $q->whereIn('profession_skills.profession_id', $professions_group);
                })->paginate($this->per_page);

        } else {
            $items = Skill::where('name_' . $lang, 'like', '%' . $term . '%')
                ->orderBy('name_' . $lang, 'asc')
                ->paginate($this->per_page);
        }

        $next_page_number = null;
        if ($items->nextPageUrl()) {
            $t = parse_url($items->nextPageUrl());
            $t = isset($t["query"]) ? $t["query"] : "";
            $t = explode("=", $t);
            $next_page_number = in_array("page", $t) ? $t[array_search("page", $t) + 1] : null;
        }

        $data = [
            "items" => [],
            "next" => $next_page_number,
        ];

        foreach ($items as $item) {
            $data["items"][] = [
                'id' => $item->id,
                'name' => $item->getAttribute('name_' . $lang)
            ];
        }

        $message = new Message(__('api/messages.skills.title'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function courseRate(Request $request)
    {
        $hash = $request->header('hash');
        $lang = $request->header('lang', 'ru');
        $user_id = $request->get('user');
        $course_id = $request->get('course');
        $rate = $request->get('rate');
        $description = $request->get('description');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'course' => 'required',
            'description' => 'required',
            'rate' => 'required',
            'user' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'course' => $course_id,
            'description' => $description,
            'rate' => $rate,
            'user' => $user_id,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $user = User::where('id', '=', $user_id)->first();
        $course = Course::where('id', '=', $course_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        if (!$course) {
            $message = new Message(Lang::get("api/errors.course_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        $item = CourseRate::whereStudentId($user_id)->whereCourseId($course_id)->first();

        if (!$item) {
            $item = new CourseRate;
            $item->course_id = $course_id;
            $item->student_id = $user_id;
            $item->rate = $rate;
            $item->description = $description;
            $item->save();
        } else {
            $message = new Message(Lang::get("api/errors.course_rate_already_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }


        $message = new Message(__('api/messages.courses.course_rate_success'), 200, null);
        return $this->response->item($message, new MessageTransformer());
    }

    public function catalogFilter(Request $request)
    {
        $authors = $request->get('authors');
        $course_lang = $request->get('course_lang');
        $course_status = $request->get('course_status');
        $finish_date_from = $request->get('finish_date_from');
        $finish_date_to = $request->get('finish_date_to');
        $professions = $request->get('professions');
        $skills = $request->get('skills');
        $start_date_from = $request->get('start_date_from');
        $start_date_to = $request->get('start_date_to');
        $term = $request->get('term');
        $user_id = $request->get('user');

        $hash = $request->header("hash");
        $lang = $request->header("lang", 'ru');

        app()->setLocale($lang);

        // Валидация
        $rules = [
            'user' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'authors' => $authors,
            'course_lang' => $course_lang,
            'course_status' => $course_status,
            'finish_date_from' => $finish_date_from,
            'finish_date_to' => $finish_date_to,
            'professions' => $professions,
            'skills' => $skills,
            'start_date_from' => $start_date_from,
            'start_date_to' => $start_date_to,
            'term' => $term,
            'user' => $user_id,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $query = StudentCourse::where('student_id', '=', $user_id)->where('paid_status', '!=', 0)->whereHas('course', function ($q) {
            $q->where('status', '=', Course::published);
        })->orderBy('created_at', 'desc');
        // Фильтровать по фразе
        if ($term) {
            $query = $query->where(function ($q) use ($term) {
                $q->whereHas('courses', function ($q) use ($term) {
                    $q->where('courses.name', 'like', '%' . $term . '%');
                });
                $q->orWhereHas('courses.user', function ($s) use ($term) {
                    $s->where('company_name', 'like', '%' . $term . '%');
                    $s->orWhereHas('author_info', function ($k) use ($term) {
                        $arr = explode(' ', $term);
                        foreach ($arr as $key => $t) {
                            if ($key === 0) {
                                $k->where('name', 'like', '%' . $t . '%');
                                $k->orWhere('surname', 'like', '%' . $t . '%');
                            } else {
                                $k->orWhere('name', 'like', '%' . $t . '%');
                                $k->orWhere('surname', 'like', '%' . $t . '%');
                            }
                        }
                    });
                });
            });
        }

        // Сортировка по профессиям
        if ($professions) {
            $professions = json_decode($professions);
            if (count(array_filter($professions)) > 0) {
                $query->whereHas('courses.professions', function ($q) use ($professions) {
                    $q->whereIn('professions.id', $professions);
                });
            }
        }
        // Сортировка по навыкам
        if ($skills) {
            $skills = json_decode($skills);
            if (count(array_filter($skills)) > 0) {
                $query->whereHas('courses.skills', function ($q) use ($skills) {
                    $q->whereIn('skills.id', $skills);
                });
            }
        }
        // Сортировка по авторам
        if ($authors) {
            $authors = json_decode($authors);
            if (count(array_filter($authors)) > 0) {
                $query->whereHas('courses.users', function ($q) use ($authors) {
                    $q->whereIn('users.id', $authors);
                });
            }
        }
        // Сортировка по языку
        if ($course_lang) {
            $course_lang = json_decode($course_lang);
            if (count($course_lang) > 0) {
                $query->whereHas('courses', function ($q) use ($course_lang) {
                    $q->whereIn('courses.lang', $course_lang);
                });
            }
        }
        // Сортировка по статусу
        if ($course_status) {
            $query->whereIn('is_finished', json_decode($course_status));
        }
        // Сортировка по Дате записи на курс
        if ($start_date_from and empty($start_date_to)) {
            $query->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($start_date_from)));
        } else if ($start_date_to and empty($start_date_from)) {
            $query->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($start_date_to)));
        } else if ($start_date_to and $start_date_from) {
            $query->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($start_date_from)), date('Y-m-d 23:59:59', strtotime($start_date_to))]);
        }
        // Сортировка по Дате окончания курса
        if ($finish_date_to and empty($start_date_to)) {
            $query->where('updated_at', '>=', date('Y-m-d 00:00:00', strtotime($finish_date_from)))->where('is_finished', '=', true);
        } else if ($finish_date_to and empty($finish_date_from)) {
            $query->where('updated_at', '<=', date('Y-m-d 23:59:59', strtotime($finish_date_to)))->where('is_finished', '=', true);
        } else if ($finish_date_to and $finish_date_from) {
            $query->whereBetween('updated_at', [date('Y-m-d 00:00:00', strtotime($finish_date_from)), date('Y-m-d 23:59:59', strtotime($finish_date_to))])->where('is_finished', '=', true);
        }

        $items = $query->paginate($this->per_page);

        $next_page_number = null;
        if ($items->nextPageUrl()) {
            $t = parse_url($items->nextPageUrl());
            $t = isset($t["query"]) ? $t["query"] : "";
            $t = explode("=", $t);
            $next_page_number = in_array("page", $t) ? $t[array_search("page", $t) + 1] : null;
        }

        $data = [
            "items" => [],
            "next" => $next_page_number,
        ];

        foreach ($items as $item) {
            $lessonsCount = Lesson::whereCourseId($item->course_id)
                ->whereIn('type', [1, 2])
                ->count();
            $finishedLessonsCount = Lesson::whereCourseId($item->course_id)
                ->whereIn('type', [1, 2])
                ->whereHas('student_lessons', function ($q) {
                    $q->where('student_lesson.is_finished', '=', true);
                })
                ->count();

            if ($lessonsCount === 0) {
                $item->progress = 100;
            } else {
                $item->progress = round($finishedLessonsCount / $lessonsCount * 100);
            }

            if (!empty($item->is_finished) == true) {
                $end = $item->updated_at->format('Y-m-d');
                $certificate = StudentCertificate::whereCourseId($item->course->id)->whereUserId($user_id)->first()['pdf_' . $lang] ?? StudentCertificate::whereCourseId($item->course->id)->whereUserId($user_id)->first()['pdf_ru'];
                if ($certificate != null) {
                    $certificate = env('APP_URL') . $certificate;
                }
            } else {
                $end = null;
                $certificate = null;
            }
            $data["items"][] = [
                'id' => $item->course->id,
                'image' => env('APP_URL') . $item->course->getAvatar(),
                'name' => $item->course->name,
                'author' => $item->course->user->author_info->name . ' ' . $item->course->user->author_info->surname,
                'author_company_name' => $item->course->user->company_name,
                'authorId' => $item->course->user->id,
                'authorImage' => env('APP_URL') . $item->course->user->author_info->getAvatar(),
                'authorSpeciality' => implode(', ', json_decode($item->course->user->author_info->specialization) ?? []),
                'authorInfo' => $item->course->user->author_info->about,
                'start' => $item->created_at->format('Y-m-d'),
                'end' => $end,
                'certificate' => $certificate,
                'percent' => $item->progress,
                'is_rate' => CourseRate::whereStudentId($user_id)->whereCourseId($item->course->id)->exists()
            ];
        }

        $message = new Message(__('api/messages.courses.title'), 200, $data);
        return $this->response->item($message, new MessageTransformer());

    }

    public function courseView(Request $request)
    {
        $course_id = $request->get('course');
        $user_id = $request->get('user');
        $hash = $request->header('hash');
        $lang = $request->header('lang', 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'course' => 'required',
            'user' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'course' => $course_id,
            'user' => $user_id,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $course = Course::whereId($course_id)->first();
        $user = User::whereId($user_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        if (!$course) {
            $message = new Message(Lang::get("api/errors.course_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        $student_course = StudentCourse::whereStudentId($user->id)->whereCourseId($course->id)->first();
        if (!$student_course or $student_course->paid_status == 0) {
            $message = new Message(Lang::get("api/errors.user_doesnt_have_course"), 403, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(403);
        }

        // Уроки
        $themes = [];
        foreach ($course->themes->sortBy('index_number') as $key => $theme) {
            $themes[] = ['id' => $theme->id, 'name' => $theme->name];
            foreach ($theme->lessons->sortBy('index_number') as $lesson) {
                $this->lessonViewAccess($lang, $course, $lesson, $user);
                $end_lesson_type = $lesson->end_lesson_type == 0 ? ' (' . __('default.pages.lessons.test_title') . ')' : ' (' . __('default.pages.lessons.homework_title') . ')';
                $themes[$key]['lessons'][] = [
                    'id' => $lesson->id,
                    'name' => $lesson->name,
                    'type' => $lesson->type,
                    'finished' => $lesson->lesson_student->is_finished ?? 0,
                    'duration' => $lesson->duration,
                    'enabled' => $lesson->lesson_student->is_access ?? 0,
                    'end_lesson_type' => $lesson->end_lesson_type
                ];
            }
        }
        // Курсовая работа
        $coursework = null;
        if (!empty($course->courseWork())) {
            $coursework = [
                'id' => $course->courseWork()->id,
                'finished' => $course->courseWork()->lesson_student->is_finished ?? 0,
                'duration' => $course->courseWork()->duration,
                'enabled' => $course->courseWork()->lesson_student->is_access ?? 0,
                'end_lesson_type' => $course->courseWork()->end_lesson_type
            ];
        }
        // Финальный тест
        $final_test = null;
        if (!empty($course->finalTest())) {
            $final_test = [
                'id' => $course->finalTest()->id,
                'finished' => $course->finalTest()->lesson_student->is_finished ?? 0,
                'duration' => $course->finalTest()->duration,
                'enabled' => $course->finalTest()->lesson_student->is_access ?? 0,
                'end_lesson_type' => $course->finalTest()->end_lesson_type
            ];
        }
        // Профессии
        $professions = [];
        foreach ($course->professions->groupBy('id') as $profession) {
            $professions[] = [
                'id' => $profession[0]->id,
                'name' => $profession[0]['name_' . $lang]
            ];
        }
        // Навыки
        $skills = [];
        foreach ($course->skills->groupBy('id') as $skill) {
            $skills[] = [
                'id' => $skill[0]->id,
                'name' => $skill[0]['name_' . $lang]
            ];
        }
        // Вложения
        if (!empty($course->attachments)) {
            // Видео
            if ($course->is_poor_vision == true) {
                $videos_array = array_merge(json_decode($course->attachments->videos), json_decode($course->attachments->videos_poor_vision));
            } else {
                $videos_array = json_decode($course->attachments->videos);
            }

            if ($videos_array != []) {
                foreach ($videos_array as $video) {
                    $videos[] = [
                        'url' => env('APP_URL') . $video
                    ];
                }
            } else {
                $videos = null;
            }
            // Видео с YouTube
            if ($course->is_poor_vision == true) {
                $youtube_videos_array = array_merge(json_decode($course->attachments->videos_link), json_decode($course->attachments->videos_poor_vision_link));
            } else {
                $youtube_videos_array = json_decode($course->attachments->videos_link);
            }

            if ($youtube_videos_array != []) {
                foreach ($youtube_videos_array as $video) {
                    $youtube_videos[] = [
                        'id' => YoutubeParse::parseYoutube($video)
                    ];
                }
            } else {
                $youtube_videos = null;
            }
            // Аудио
            if ($course->is_poor_vision == true) {
                $audios_array = array_merge(json_decode($course->attachments->audios), json_decode($course->attachments->audios_poor_vision));
            } else {
                $audios_array = json_decode($course->attachments->audios);
            }

            if ($audios_array != []) {
                foreach ($audios_array as $audio) {
                    $audios[] = [
                        'url' => env('APP_URL') . $audio
                    ];
                }
            } else {
                $audios = null;
            }
        } else {
            $videos = null;
            $youtube_videos = null;
            $audios = null;
        }

        $courses = $course->user->courses()->get();
        // Все оценки всех курсов
        $rates = [];
        foreach ($courses as $item) {
            foreach ($item->rate as $rate) {
                array_push($rates, $rate->rate);
            }
        }

        $lessonsCount = Lesson::whereCourseId($course->id)
            ->whereIn('type', [1, 2])
            ->count();
        $finishedLessonsCount = Lesson::whereCourseId($course->id)
            ->whereIn('type', [1, 2])
            ->whereHas('student_lessons', function ($q) {
                $q->where('student_lesson.is_finished', '=', true);
            })
            ->count();

        if ($lessonsCount === 0) {
            $course->progress = 100;
        } else {
            $course->progress = round($finishedLessonsCount / $lessonsCount * 100);
        }

        if (!empty($student_course->is_finished) == true) {
            $end = $student_course->updated_at->format('Y-m-d');
            $certificate = StudentCertificate::whereCourseId($course->id)->whereUserId($user->id)->first()['pdf_' . $lang] ?? null;
        } else {
            $end = null;
            $certificate = null;
        }
        $course_lessons = $course->lessons;
        $videos_count = [];
        $audios_count = [];
        $attachments_count = [];

        foreach ($course_lessons as $lesson) {
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

        $includes = View::make("app.pages.general.courses.catalog.components.course_includes", ['item' => $course, 'videos_count' => array_sum($videos_count), 'audios_count' => array_sum($audios_count), 'attachments_count' => array_sum($attachments_count)])->render();

        $data = [
            'id' => $course->id,
            'name' => $course->name,
            'is_student_paid' => $student_course->paid_status != 0 ? true : false,
            'is_access_all' => $course->is_access_all,
            'is_poor_vision' => $course->is_poor_vision,
            'cost' => $course->cost,
            'profit' => $course->profit_desc,
            'start' => $course->created_at->format('Y-m-d'),
            'end' => $end,
            'certificate' => $certificate,
            'percent' => $course->progress,
            'teaser' => $course->teaser,
            'reviews' => count($rates),
            'students' => count($course->course_members->whereIn('paid_status', [1, 2, 3])),
            'rating' => round($course->rate->pluck('rate')->avg() ?? 0, 1),
            'lang' => $course->lang == 0 ? 'kk' : ($course->lang == 1 ? 'ru' : null),
            'description' => $course->description,
            'image' => env('APP_URL') . $course->getAvatar(),
            'author' => $course->user->author_info->name . ' ' . $course->user->author_info->surname,
            'videoLinks' => $videos,
            'youtubeLinks' => $youtube_videos,
            'audioLinks' => $audios,
            'includes' => $includes,
            'themes' => $themes,
            'courseWork' => $coursework,
            'finalTest' => $final_test,
            'professions' => $professions,
            'skills' => $skills,
            'authorId' => $course->user->id,
            'authorImage' => env('APP_URL') . $course->user->author_info->getAvatar(),
            'authorSpeciality' => implode(', ', json_decode($course->user->author_info->specialization) ?? []),
            'authorInfo' => $course->user->author_info->about
        ];

        $message = new Message(__('api/messages.courses.title'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function lessonViewAccess($lang, Course $course, Lesson $lesson, User $user)
    {
        $theme = $lesson->themes;

        $time = FormatDate::convertMunitesToTime($lesson->duration);

        // Если все уроки не доступны сразу
        if ($course->is_access_all == false) {
            if ($theme) {
                // Получить первый урок и первую тему из курса
                $first_theme = Theme::where('course_id', '=', $course->id)->orderBy('index_number', 'asc')->first();
                $first_lesson = Lesson::where('theme_id', '=', $theme->id)->orderBy('index_number', 'asc')->first();

                // Проверить является ли урок первым в курсе
                if (($theme->id == $first_theme->id) and ($first_lesson->id == $lesson->id)) {

                    $this->syncUserLessons($lesson->id, $user);

                    // Если урок не является первым в курсе
                } else {
                    // Получить предыдущую тему и урок из этой темы
                    $previous_theme = Theme::where('course_id', '=', $course->id)->where('index_number', '<', $theme->index_number)->orderBy('index_number', 'desc')->first();
                    $previous_lesson_theme = Lesson::where('index_number', '<', $lesson->index_number)->where('theme_id', '=', $theme->id)->orderBy('index_number', 'desc')->first();
                    // Если предыдущая тема есть, то получить урок из предыдущей темы
                    if (!empty($previous_theme)) {
                        $previous_lesson = Lesson::where('theme_id', '=', $previous_theme->id)->orderBy('index_number', 'desc')->first();
                    }
                    // Если есть урок из предыдущей темы и он завершен, дать доступ к текущему уроку
                    if (!empty($previous_lesson_theme)) {
                        if (!empty($previous_lesson_theme->lesson_student->is_finished) == true) {
                            $this->syncUserLessons($lesson->id, $user);

                        }
                    } else {
                        // Если есть урок и он завершен, дать доступ к текущему уроку
                        if (!empty($previous_lesson) and !empty($previous_lesson->lesson_student->is_finished) == true) {
                            $this->syncUserLessons($lesson->id, $user);

                        }
                    }
                }
            } else {
                // Проверить завершенность уроков
                $all_course_lessons = $course->lessons()->whereNotIn('type', [3, 4])->pluck('id')->toArray();
                $finished_lessons = $user->student_lesson()->where('course_id', '=', $course->id)->where('is_finished', '=', true)->pluck('lesson_id')->toArray();
                // Если все курсы завершены
                if (array_diff($all_course_lessons, $finished_lessons) == []) {
                    // Если это курсовая работа, дать доступ
                    if ($lesson->type == 3) {
                        $this->syncUserLessons($lesson->id, $user);
                        // Если это финальный тест, проверить завершена ли курсовая
                    } else if ($lesson->type == 4) {
                        $coursework = $course->lessons()->where('type', '=', 3)->first();
                        if ($coursework) {
                            // Если есть курсовая и она завершена, дать доступ
                            if (!empty($coursework->lesson_student->is_finished) == true) {
                                $this->syncUserLessons($lesson->id, $user);
                            }
                            // Если есть курсовой нет, то дать доступ
                        } else {
                            $this->syncUserLessons($lesson->id, $user);
                        }
                    }

                }

            }
            // Если все уроки доступны сразу
        } else {
            // Проверить завершенность уроков
            $all_course_lessons = $course->lessons()->whereNotIn('type', [3, 4])->pluck('id')->toArray();
            $finished_lessons = $user->student_lesson()->where('course_id', '=', $course->id)->where('is_finished', '=', true)->pluck('lesson_id')->toArray();
            switch ($lesson->type) {
                case (3):
                    if (array_diff($all_course_lessons, $finished_lessons) == []) {
                        $this->syncUserLessons($lesson->id, $user);
                    }
                    break;
                case (4):
                    $coursework = $course->lessons()->where('type', '=', 3)->first();
                    if ($coursework) {
                        // Если есть курсовая и она завершена, дать доступ
                        if (!empty($coursework->lesson_student->is_finished) == true) {
                            $this->syncUserLessons($lesson->id, $user);
                            // Если есть курсовая, но она не завершена, вернуть обратно
                        }
                        // Если есть курсовой нет, то дать доступ
                    } else {
                        $this->syncUserLessons($lesson->id, $user);
                    }
                    break;
                default:
                    $this->syncUserLessons($lesson->id, $user);
                    break;
            }

        }

    }

    public function syncUserLessons(int $lesson_id, User $user)
    {
        $item = StudentLesson::where('lesson_id', '=', $lesson_id)->where('student_id', '=', $user->id)->first();
        if (empty($item)) {
            $item = new StudentLesson;
            $item->lesson_id = $lesson_id;
            $item->student_id = $user->id;
            $item->is_access = true;
            $item->save();
        }

    }
}
