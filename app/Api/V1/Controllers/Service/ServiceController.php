<?php

namespace App\Api\V1\Controllers\Service;

use App\Api\V1\Classes\Message;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Transformers\MessageTransformer;
use App\Models\Course;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ServiceController extends BaseController
{
    public function getSkillsByUid(Request $request)
    {
        $uid = $request->get('uid');
        $hash = $request->header("hash");
        $lang = $request->header("lang", 'ru');
        app()->setLocale($lang);

        // Валидация
        $validator = Validator::make($request->all(), [
            'uid' => 'required'
        ]);
        $validator = Validator::make($request->header(), [
            'hash' => 'required',
        ]);

        if ($hash = $this->validateHash(['uid' => $uid, 'hash' => $hash], env('APP_DEBUG'))) {
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

        // Получить навыки обучающегося
        $skills = Skill::whereHas('student_skill.student_info', function ($q) use ($uid) {
            $q->whereUid($uid);
        })->get();

        $data = [];
        foreach ($skills as $skill) {
            $data[] = [
                'code_skill' => $skill->code_skill,
                'name_ru' => $skill->name_ru,
                'name_kk' => $skill->name_kk,
                'name_en' => $skill->name_en,
            ];
        }

        $message = new Message(__('api/messages.success'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function getSkillsByIin(Request $request)
    {
        $iin = $request->get('iin');
        $hash = $request->header("hash");
        $lang = $request->header("lang", 'ru');
        app()->setLocale($lang);

        // Валидация
        $validator = Validator::make($request->all(), [
            'uid' => 'required'
        ]);
        $validator = Validator::make($request->header(), [
            'hash' => 'required',
        ]);

        if ($hash = $this->validateHash(['uid' => $iin, 'hash' => $hash], env('APP_DEBUG'))) {
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

        // Получить навыки обучающегося
        $skills = Skill::whereHas('student_skill.student_info', function ($q) use ($iin) {
            $q->whereIin($iin);
        })->get();

        $data = [];
        foreach ($skills as $skill) {
            $data[] = [
                'code_skill' => $skill->code_skill,
                'name_ru' => $skill->name_ru,
                'name_kk' => $skill->name_kk,
                'name_en' => $skill->name_en,
            ];
        }

        $message = new Message(__('api/messages.success'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function getSearchResult(Request $request)
    {
        $course_lang = (string)$request->get('course_lang');
        $course_sort = $request->get('sort_type');
        $course_type = $request->get('course_type');
        $finished_students_min = $request->get('finished_students_min');
        $lang = $request->get("lang", 'ru');
        $professions = (string)$request->get('professions');
        $rate_min = $request->get('rate_min');
        $skills = (string)$request->get('skills');
        $term = $request->get('term');

        $hash = $request->header("hash");

        app()->setLocale($lang);

//        // Валидация
//        $validator = Validator::make($request->header(), [
//            'hash' => 'required',
//        ]);
//
//        if ($hash = $this->validateHash(['course_lang' => $course_lang, 'course_sort' => $course_sort, 'course_type' => $course_type
//            , 'finished_students_min' => $finished_students_min, 'professions' => $professions,
//            'rate_min' => $rate_min, 'skills' => $skills, 'term' => $term, 'hash' => $hash], env('APP_DEBUG'))) {
//            if (is_bool($hash)) {
//                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
//            } else {
//                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
//            }
//        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $query = Course::where('status', '=', Course::published);
        // Фильтровать по фразе
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
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
        switch ($course_sort) {
            case 'sort_by_rate_high':
                $query->withCount(['rate as average_rate' => function ($query) {
                    $query->select(DB::raw('round(avg(rate),1)'));
                }])->orderBy('average_rate', 'desc');
                break;
            case 'sort_by_rate_low':
                $query->withCount(['rate as average_rate' => function ($query) {
                    $query->select(DB::raw('round(avg(rate),1)'));
                }])->orderBy('average_rate', 'asc');
                break;
            case 'sort_by_cost_high':
                $query->orderBy('cost', 'desc');
                break;
            case 'sort_by_cost_low':
                $query->orderBy('cost', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        // Учеников, окончивших курс (мин)
        if ($finished_students_min) {
            $query->withCount(['course_members' => function ($q) {
                $q->where('student_course.is_finished', '=', true);
                $q->whereIn('paid_status', [1, 2]);
            }])->having('course_members_count', '>=', $finished_students_min);
        }
        // Получить профессии
        if ($professions) {
            $professions = Professions::whereIn('id', json_decode($professions))->pluck('id');
            $query->whereHas('professions', function ($q) use ($professions) {
                $q->whereIn('professions.id', json_decode($professions));
            });
        }
        // Сортировка по навыкам
        if ($skills) {
            $skills = Skill::whereIn('code_skill', json_decode($skills))->pluck('id');
            $query->whereHas('skills', function ($q) use ($skills) {
                $q->whereIn('skills.id', json_decode($skills));
            });
        }
        // Сортировка по языку
        if ($course_lang) {
            $query->whereIn('lang', json_decode($course_lang));
        }
        // Рейтинг от
        if ($rate_min) {
            $query->whereHas('rate', function ($q) use ($rate_min) {
                $q->where('course_rate.rate', '>=', $rate_min);
            });
        }

        $items = $query->get();

        if (json_decode($course_lang) == [0]) {
            $data_lang = 'lang_kk=1';
        } else if (json_decode($course_lang) == [1]) {
            $data_lang = 'lang_ru=1';
        } else {
            $data_lang = 'lang_kk=1&lang_ru=1';
        }

        $professions_data = '';
        if ($professions) {
            foreach ($professions as $profession) {
                $professions_data .= '&specialities[]=' . $profession . '&';
            }
        }

        $skills_data = '';
        if ($skills) {
            foreach ($skills as $skill) {
                $skills_data .= '&skills[]=' . $skill . '&';
            }
        }

        $data = ['results_count' => $items->count(),
            'search_link' => env('APP_URL') . '/' . $lang . '/course-catalog?search=' . $term . $professions_data . $skills_data . '&' . $data_lang . '&min_rating=' . $rate_min . '&members_count=' . $finished_students_min . '&course_type=' . $course_type . '&course_sort=' . $course_sort . ''];


        $message = new Message(__('api/messages.success'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function updateSkillsByUid(Request $request)
    {
        $uid = $request->get('uid');
        $include_skills = (string)$request->get('include_skills');
        $exclude_skills = (string)$request->get('exclude_skills');
        $lang = $request->header('lang', 'ru');
        $hash = $request->header('hash');
        app()->setLocale($lang);

        // Валидация
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
        ]);
        $validator = Validator::make($request->header(), [
            'hash' => 'required'
        ]);

//        if ($hash = $this->validateHash(['exclude_skills' => $exclude_skills, 'include_skills' => $include_skills, 'uid' => $uid, 'hash' => $hash], env('APP_DEBUG'))) {
        if ($hash = $this->validateHash(['uid' => $uid, 'hash' => $hash], env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        // Получить обучающегося по uid
        $user = User::whereHas('student_info', function ($q) use ($uid) {
            $q->whereUid($uid);
        })->first();

        // Получить навыки по code_skill
        if ($include_skills) {
            $include_skills = Skill::whereIn('code_skill', json_decode($include_skills))->pluck('id');
        }
        if ($exclude_skills) {
            $exclude_skills = Skill::whereIn('code_skill', json_decode($exclude_skills))->pluck('id');
        }

        if ($user === null) {
            $validator->errors()->add('user', __('api/errors.user_does_not_exist'));
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($include_skills) {
            $user->skills()->sync($include_skills, false);
        }
        if ($exclude_skills) {
            $user->skills()->detach($exclude_skills);
        }

        $message = new Message(__('api/messages.success'), 200, null);
        return $this->response->item($message, new MessageTransformer());
    }

    public function updateQuotaByUid(Request $request)
    {
        $uid = $request->get('uid');
        $lang = $request->header('lang', 'ru');
        $hash = $request->header('hash');
        app()->setLocale($lang);
        $quota_count = 3;

        // Валидация
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
        ]);
        $validator = Validator::make($request->header(), [
            'hash' => 'required'
        ]);

        if ($hash = $this->validateHash(['uid' => $uid, 'hash' => $hash], env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        // Получить обучающегося по uid
        $user = User::whereHas('student_info', function ($q) use ($uid) {
            $q->whereUid($uid);
        })->first();

        if ($user === null) {
            $validator->errors()->add('user', __('api/errors.user_does_not_exist'));
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $user->student_info->quota_count = $quota_count;
        $user->student_info->save();

        $message = new Message(__('api/messages.success'), 200, null);
        return $this->response->item($message, new MessageTransformer());
    }
}
