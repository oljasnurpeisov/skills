<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\StudentCourse;
use App\Models\Type_of_ownership;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchestra\Parser\Xml\Facade as XmlParser;


class PageController extends Controller
{
    public function index(Request $request, $lang = "ru")
    {
        if (Auth::check()) {
            // Получить навыки пользователя и записать их в массив
            $user_skills = User::where('id', '=', Auth::user()->id)->with('skills')->first();

            $skill_ids = array();
            foreach ($user_skills->skills as $skill) {
                array_push($skill_ids, $skill->id);
            }
            // Получить список профессий из навыков пользователя
            $profession_skills = Professions::whereHas('skills', function ($query) use ($skill_ids) {
                $query->whereIn('skill_id', $skill_ids);
            })->pluck('id')->toArray();

            // Получить список навыков из списка профессий
            $skills_professions = Skill::whereHas('professions', function ($query) use ($profession_skills) {
                $query->whereIn('profession_id', $profession_skills);
//                $query->where('fl_check', '=', 1)->where('fl_show', '=', 1);
            })->pluck('id')->toArray();

            // Получить список курсов из списка навыков профессий
            $courses = Course::where('status', '=', Course::published)->whereHas('skills', function ($query) use ($skills_professions) {
                $query->whereIn('skill_id', $skills_professions);
            })->limit(12)->get();

//            $skills = Skill::where('fl_check', '=', 1)->where('fl_show', '=', 1)->whereIn('id', $skills_professions)->limit(18)->get();
            $skills = Skill::whereIn('id', $skills_professions)->limit(18)->get();

        }

        return view("welcome", [
            "courses" => $courses ?? [],
            "skills" => $skills ?? [],
        ]);
    }

    public function courseCatalog(Request $request, $lang)
    {

        $professions = Professions::orderBy('name_' . $lang, 'asc')->paginate(500);

        $skills = collect();

        $term = $request->term ? $request->term : '';
        $choosed_profession = $request->choosed_profession ? $request->choosed_profession : '';
        $choosed_skills = $request->choosed_skills;
        $course_type = $request->course_type ?? [0, 1];
        $choosed_lang = $request->choosed_lang ?? [0, 1];

        $query = Course::where('status', '=', Course::published);
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }
//        if ($course_type) {
        if ($course_type == 1) {
            $query = $query->where('is_paid', '=', 1);
        } else if ($course_type == 0) {
            $query = $query->where('is_paid', '=', 0);
        } else {
            $query = $query->whereIn('is_paid', $course_type);
        }

        if ($choosed_lang == 1) {
            $query = $query->where('lang', '=', 1);
        } else if ($choosed_lang == 0) {
            $query = $query->where('lang', '=', 0);
        } else {
            $query = $query->whereIn('lang', $choosed_lang);
        }
//        }
        if (!empty($choosed_skills)) {
            $query = $query->whereHas('skills', function ($q) use ($choosed_skills) {
                $q->where('skills.id', '=', $choosed_skills);
            });
        }
        if ($choosed_profession) {
            $skills = Skill::whereHas('professions', function ($q) use ($choosed_profession) {
                $q->where('professions.id', '=', $choosed_profession);
            })->get();
        }
        $items = $query->where('status', '=', Course::published)->paginate();
        return view("app.pages.general.courses.catalog.course_catalog", [
            "items" => $items,
            'term' => $term,
            'professions' => $professions,
            'skills' => $skills,
            'choosed_profession' => $choosed_profession
        ])->render();
//        return $course_type;
    }

    public function courseView($lang, Course $item)
    {
        if ($item->status == 3) {
            $themes = $item->themes()->orderBy('index_number', 'asc')->get();
            $lessons_count = 0;
            foreach ($themes as $theme) {
                foreach ($theme->lessons()->get() as $lesson) {
                    $lessons_count++;
                }
            }
            if (Auth::check()) {
                $student_course = StudentCourse::where('student_id', '=', Auth::user()->id)->where('course_id', '=', $item->id)->first();
            }

            return view("app.pages.general.courses.catalog.course_view", [
                "item" => $item,
                "themes" => $themes,
                "lessons_count" => $lessons_count,
                "student_course" => $student_course ?? []
            ]);
        } else {
            return redirect("/" . app()->getLocale() . "/course-catalog");
        }
    }

    public function courseSearch(Request $request, $lang)
    {
        $professions = Professions::orderBy('name_' . $lang, 'asc')->paginate(500);

        $skills = collect();

        $term = $request->term ? $request->term : '';
        $choosed_profession = $request->choosed_profession ? $request->choosed_profession : '';
        $choosed_skills = $request->choosed_skills;
        $course_type = $request->course_type ?? [0, 1];
        $choosed_lang = $request->choosed_lang ?? [0, 1];

        $query = Course::where('status', '=', Course::published);
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }
//        if ($course_type) {
        if ($course_type == 1) {
            $query = $query->where('is_paid', '=', 1);
        } else if ($course_type == 0) {
            $query = $query->where('is_paid', '=', 0);
        } else {
            $query = $query->whereIn('is_paid', $course_type);
        }

        if ($choosed_lang == 1) {
            $query = $query->where('lang', '=', 1);
        } else if ($choosed_lang == 0) {
            $query = $query->where('lang', '=', 0);
        } else {
            $query = $query->whereIn('lang', $choosed_lang);
        }
//        }
        if (!empty($choosed_skills)) {
            $query = $query->whereHas('skills', function ($q) use ($choosed_skills) {
                $q->where('skills.id', '=', $choosed_skills);
            });
        }
        if ($choosed_profession) {
            $skills = Skill::whereHas('professions', function ($q) use ($choosed_profession) {
                $q->where('professions.id', '=', $choosed_profession);
            })->get();
        }
        $items = $query->where('status', '=', Course::published)->paginate();
        return [
            "items" => $items,
            'term' => $term,
            'professions' => $professions,
            'skills' => $skills
        ];
    }

    public function courseCatalogFilter(Request $request, $lang)
    {

        $term = $request->term ? $request->term : '';
        $choosed_profession = $request->choosed_profession ?? '';
        $choosed_lang_ru = $request->choosed_lang_ru ?? null;
        $choosed_lang_kk = $request->choosed_lang_kk ?? null;
        $course_type = $request->course_type;

        // Создание массив из языков
        $languages = array();
        if ($choosed_lang_ru != null) {
            array_push($languages, $choosed_lang_ru);
        }
        if ($choosed_lang_kk != null) {
            array_push($languages, $choosed_lang_kk);
        }
        $query = Course::where('status', '=', Course::published);
        // Найти по тексту
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }
        // Найти по языку
        if (!empty($languages)) {
            $query = $query->whereIn('lang', $languages);
        }
        // Найти по типу курса
        if ($course_type != null) {
            $query = $query->where('is_paid', '=', $course_type);
        }
        $items = $query->where('status', '=', Course::published)->paginate();
        return $items;
    }
}
