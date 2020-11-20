<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRate;
use App\Models\Lesson;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\StudentCourse;
use App\Models\StudentLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CourseController extends Controller
{
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
        if ($item->status == Course::published) {
            $themes = $item->themes()->orderBy('index_number', 'asc')->get();
            $lessons_count = count(Lesson::where('course_id', '=', $item->id)->get());
            if (Auth::check()) {
                $student_course = StudentCourse::where('student_id', '=', Auth::user()->id)->where('course_id', '=', $item->id)->first();
                $coursework = $item->lessons->where('type', '=', 3)->first();
                $final_test = $item->lessons->where('type', '=', 4)->first();
                $student_rate = CourseRate::where('student_id', '=', Auth::user()->id)->where('course_id', '=', $item->id)->first();

            }

            return view("app.pages.general.courses.catalog.course_view", [
                "item" => $item,
                "themes" => $themes,
                "lessons_count" => $lessons_count,
                "student_course" => $student_course ?? [],
                "student_rate" => $student_rate ?? [],
                "coursework" => $coursework,
                "final_test" => $final_test
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
        $skills = collect();

        $term = $request->term ? $request->term : '';
        $choosed_profession = $request->professions ?? '';
        $choosed_lang_ru = $request->choosed_lang_ru ?? null;
        $choosed_lang_kk = $request->choosed_lang_kk ?? null;
        $course_type = $request->course_type;
        $choosed_skills = $request->choosed_skills;


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
        if ($choosed_profession) {
            $skills = Skill::whereHas('professions', function ($q) use ($choosed_profession) {
                $q->where('professions.id', '=', $choosed_profession);
            })->get();
        }
        $items = $query->where('status', '=', Course::published)->paginate();
//        return [$items, $skills];
        return $skills;
    }


    public function getProfessionsByName(Request $request, $lang)
    {
        $profession_name = $request->name ?? '';

        $professions = Professions::where('name_' . $lang, 'like', '%' . $profession_name . '%')->orderBy('name_' . $lang, 'asc')->limit(50)->get();

        return $professions;
    }

    public function getSkillsByData(Request $request, $lang)
    {
        $professions = $request->professions;
        $skill_name = $request->name ?? '';

        $skills = $skills = Skill::where('name_' . $lang, 'like', '%' . $skill_name . '%')->whereHas('professions', function ($q) use ($professions) {
            if ($professions != []) {
                $q->whereIn('professions.id', $professions);
            }
        })->where('fl_check', '=', '1')->where('fl_show', '=', '1')->where('uid', '=', null)->limit(50)->get();

        return $skills;
    }

//    public function getSkillsByName(Request $request, $lang){
//        $skill_name = $request->name ?? '';
//
//        $skills = Skill::where('name_'.$lang, 'like', '%' . $skill_name . '%')->limit(50)->get();
//
//        return $skills;
//    }
}
