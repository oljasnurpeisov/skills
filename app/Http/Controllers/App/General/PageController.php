<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

        return view("welcome", [
            "courses" => $courses ?? [],
            "skills" => $skills ?? [],
        ]);
    }

    public function notifications(){

        $notifications = Auth::user()->notifications()->paginate(5);

        return view("app.pages.general.notifications.notifications", [
            'notifications' => $notifications
        ]);
    }

}
