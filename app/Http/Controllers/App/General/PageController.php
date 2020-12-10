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
use PharIo\Manifest\Author;


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
                $query->where('fl_check', '=', 1)->where('fl_show', '=', 1)->where('uid', '=', null);
            })->pluck('id')->toArray();

            // Получить список курсов из списка навыков профессий
            $courses = Course::where('status', '=', Course::published)->whereHas('skills', function ($query) use ($skills_professions) {
                $query->whereIn('skill_id', $skills_professions);
            })->limit(12)->get();
            $skills = Skill::where('fl_check', '=', 1)->where('fl_show', '=', 1)->where('uid', '=', null)->whereIn('id', $skills_professions)->limit(18)->get();
//            $skills = Skill::whereIn('id', $skills_professions)->limit(18)->get();

        }

        $popular_courses = Course::withCount('course_members')->orderBy('course_members_count', 'desc')->where('status', '=', Course::published)->limit(8)->get();
        $popular_authors = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'author');
        })->where('email_verified_at', '!=', null)->where('is_activate', '=', true)->with('courses')->get();

        // Получить количество записавшихся на курсы
        foreach ($popular_authors as $author) {
            $author->unique_members = 0;
            $author->members = 0;
            $author->rates = 0;
            $rates_array = [];

//            $rates = $author->rates_array;
//            $author->rates_array = [];

            foreach ($author->courses as $course) {
                // Количество купленных курсов
                foreach ($course->course_members as $member) {
                    $author->members = count($course->course_members);
                }
                // Количество отзывов
                foreach ($course->rate as $rate) {
                    $author->rates++;

                    $rates_array[] = $rate->rate;

                }
            }
            // Уникальные пользователи
            foreach ($author->courses->unique('student_id') as $course) {
                foreach ($course->course_members as $member) {
                    $author->unique_members = count($course->course_members);
                }
            }
            // Оценка автора исходя из всех оценок
            if (count($rates_array) == 0) {
                $author->average_rates = 0;
            } else {
                $author->average_rates = array_sum($rates_array) / count($rates_array);
            }
        }

        $popular_authors = $popular_authors->sortByDesc("members")->take(8);


        return view("index", [
            "courses" => $courses ?? [],
            "skills" => $skills ?? [],
            "popular_courses" => $popular_courses,
            "popular_authors" => $popular_authors,
        ]);
    }

    public function for_authors(){
        return view("for_authors", [

        ]);
    }

    public function faq(){
        return view("app.pages.general.faq", [

        ]);
    }

    public function notifications()
    {

        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->paginate(5);

        return view("app.pages.general.notifications.notifications", [
            'notifications' => $notifications
        ]);

    }

}
