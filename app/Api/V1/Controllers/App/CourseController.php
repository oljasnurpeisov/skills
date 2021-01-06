<?php

namespace App\Api\V1\Controllers\App;

use App\Api\V1\Classes\Message;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Transformers\MessageTransformer;
use App\Models\Course;
use App\Models\CourseRate;
use App\Models\Lesson;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\StudentCertificate;
use App\Models\StudentCourse;
use App\Models\Theme;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

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
        });
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
            if (count(array_filter($professions)) > 0) {
                $query->whereHas('courses.professions', function ($q) use ($professions) {
                    $q->whereIn('professions.id', json_decode($professions));
                });
            }
        }
        // Сортировка по навыкам
        if ($skills) {
            if (count(array_filter($skills)) > 0) {
                $query->whereHas('courses.skills', function ($q) use ($skills) {
                    $q->whereIn('skills.id', json_decode($skills));
                });
            }
        }
        // Сортировка по авторам
        if ($authors) {
            $query->whereHas('courses.users', function ($q) use ($authors) {
                $q->whereIn('users.id', json_decode($authors));
            });
        }
        // Сортировка по языку
        if ($course_lang) {
            $query = $query->whereHas('courses', function ($q) use ($course_lang) {
                $q->whereIn('courses.lang', json_decode($course_lang));
            });
        }
        // Сортировка по статусу
        if($course_status) {
            $query = $query->whereIn('is_finished', json_decode($course_status));
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

            if ($item->is_finished == true) {
                $end = $item->updated_at->format('Y-m-d');
                $certificate = StudentCertificate::whereCourseId($item->course->id)->whereUserId($user_id)->first()['pdf_' . $lang] ?? null;
            } else {
                $end = null;
                $certificate = null;
            }
            $data["items"][] = [
                'id' => $item->course->id,
                'image' => env('APP_URL') . $item->course->image,
                'name' => $item->course->name,
                'author' => $item->course->user->author_info->name . ' ' . $item->course->user->author_info->surname,
                'author_company_name' => $item->course->user->company_name,
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
        if (!$student_course) {
            $message = new Message(Lang::get("api/errors.user_doesnt_have_course"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        // Уроки
        $lessons = [];
        foreach ($course->themes->sortBy('index_number') as $key => $theme) {
            foreach ($theme->lessons->sortBy('index_number') as $lesson) {
                $end_lesson_type = $lesson->end_lesson_type == 0 ? ' (' . __('default.pages.lessons.test_title') . ')' : ' (' . __('default.pages.lessons.homework_title') . ')';
                $lessons[$theme->name][] = [
                    'id' => $lesson->id,
                    'name' => $lesson->name,
                    'type' => $lesson->lesson_type['name_' . $lang] . $end_lesson_type,
                    'duration' => $lesson->duration
                ];
            }
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
                        'link' => $video
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
                        'link' => $video
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
                        'link' => $audio
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

        $data = [
            'id' => $course->id,
            'name' => $course->name,
            'lang' => $course->lang,
            'is_student_paid' => $student_course->paid_status != 0 ? true : false,
            'is_access_all' => $course->is_access_all,
            'is_poor_vision' => $course->is_poor_vision,
            'cost' => $course->cost,
            'profit_desc' => $course->profit_desc,
            'teaser' => $course->teaser,
            'description' => $course->description,
            'image' => $course->getAvatar(),
            'author' => [
                'id' => $course->user->id,
                'name' => $course->user->author_info->name . ' ' . $course->user->author_info->surname
            ],
            'videos' => $videos,
            'youtube_videos' => $youtube_videos,
            'audios' => $audios,
            'lessons' => $lessons,
            'professions' => $professions,
            'skills' => $skills,

        ];

        $message = new Message(__('api/messages.courses.title'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

}
