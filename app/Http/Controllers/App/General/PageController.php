<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Professions;
use App\Models\Skill;
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
            $user_skills = User::where('id', '=', Auth::user()->id)->with('skills')->first();

            $skill_ids = array();
            foreach ($user_skills->skills as $skill){
                array_push($skill_ids, $skill->id);
            }
            $profession_skills = Professions::whereHas('skills', function ($query) use ($skill_ids) {

                $query->whereIn('skill_id', [2]);
            })->get('id');

            $items = Course::where('status', '=', Course::published)->whereHas('skills', function ($query) use ($skill_ids) {

                $query->whereIn('skill_id', $skill_ids);
            })->limit(12)->get();
        }

        return view("welcome", [
            "items" => $items ?? [],
        ]);
//        return $profession_skills[0]->id;
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
            return view("app.pages.general.courses.catalog.course_view", [
                "item" => $item,
                "themes" => $themes,
                "lessons_count" => $lessons_count
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
}
