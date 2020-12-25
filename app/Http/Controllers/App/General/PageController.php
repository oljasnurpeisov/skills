<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Page;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\StudentInformation;
use App\Models\Type_of_ownership;
use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchestra\Parser\Xml\Facade as XmlParser;
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
            $skills_group_professions = Skill::whereHas('group_professions', function ($query) use ($profession_skills) {
                $query->whereIn('profession_id', $profession_skills);
            })->pluck('id')->toArray();

            // Получить список курсов из списка навыков профессий
            $courses = Course::where('status', '=', Course::published)->whereHas('skills', function ($query) use ($skills_group_professions) {
                $query->whereIn('skill_id', $skills_group_professions);
            })->limit(12)->get();

            $skills = Skill::whereIn('id', $skills_group_professions)->limit(18)->get();

        }

        $popular_courses = Course::withCount('course_members')->orderBy('course_members_count', 'desc')->where('status', '=', Course::published)->limit(8)->get();

        $popular_authors = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'author');
        })->whereHas('author_info', function ($q) {
            $q->where('name', '!=', null);
        })->where('email_verified_at', '!=', null)->with('courses')->get();

        // Получить количество записавшихся на курсы
        foreach ($popular_authors as $author) {
            $author->unique_members = 0;
            $author->members = 0;
            $author->rates = 0;
            $rates_array = [];
            $author_students = [];

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
                // Уникальные пользователи
                foreach ($course->course_members as $member) {
                    $author_students[$member['student_id']][] = $member;
                }
            }
            // Уникальные пользователи
            $author->members = $author_students;
            // Оценка автора исходя из всех оценок
            if (count($rates_array) == 0) {
                $author->average_rates = 0;
            } else {
                $author->average_rates = array_sum($rates_array) / count($rates_array);
            }
        }
        // Популярные авторы
        $popular_authors = $popular_authors
            ->sortByDesc("members")
            ->take(8);
        // Количество обучающихся
        $students_count = User::whereHas('roles', function ($q) {
            $q->whereSlug('student');
        })->count();
        // Количество авторов
        $authors_count = User::whereHas('roles', function ($q) {
            $q->whereSlug('author');
        })->count();
        // Количество курсов
        $courses_count = Course::whereStatus(Course::published)->count();
        // Контент
        $content = Page::wherePageAlias('index')->first();

        return view("index", [
            "courses" => $courses ?? [],
            "skills" => $skills ?? [],
            "popular_courses" => $popular_courses,
            "popular_authors" => $popular_authors,
            "students_count" => $students_count,
            "authors_count" => $authors_count,
            "courses_count" => $courses_count,
            "content" => $content,
        ]);
    }

    public function for_authors()
    {
        // Количество обучающихся
        $students_count = User::whereHas('roles', function ($q) {
            $q->whereSlug('student');
        })->count();
        // Количество авторов
        $authors_count = User::whereHas('roles', function ($q) {
            $q->whereSlug('author');
        })->count();
        // Количество курсов
        $courses_count = Course::whereStatus(Course::published)->count();
        // Контент
        $content = Page::wherePageAlias('for_authors')->first();

        return view("for_authors", [
            "students_count" => $students_count,
            "authors_count" => $authors_count,
            "courses_count" => $courses_count,
            "content" => $content
        ]);
    }

    public function faq($lang)
    {
        $items = Page::wherePageAlias('faq')->first();

        $data = [];

        if ($items->data_ru != null) {
            foreach (json_decode($items->data_ru) as $key => $item) {
                $data[$key][] = $item->name;
            }
        }
        return view("app.pages.general.faq", [
            'items' => json_decode($items->getAttribute('data_' . $lang), true)
        ]);
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->paginate(5);

        // Пометить уведомления как прочитанные
        foreach ($notifications as $item){
            $item->is_read = true;
            $item->save();
        }

        return view("app.pages.general.notifications.notifications", [
            'notifications' => $notifications
        ]);

    }

}
