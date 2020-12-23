<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRate;
use App\Models\Lesson;
use App\Models\Page;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\StudentCourse;
use App\Models\StudentLesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CourseController extends Controller
{
    public function courseCatalog(Request $request, $lang)
    {
        $query = Course::where('status', '=', Course::published);

        $lang_ru = $request->lang_ru ?? null;
        $lang_kk = $request->lang_kk ?? null;
        $course_type = $request->course_type ?? '';
        $course_sort = $request->course_sort ?? '';
        $min_rating = $request->min_rating ?? 0;
        $members_count = $request->members_count ?? 0;
        $specialities = $request->specialities;
        $skills = $request->skills;
        $term = $request->search ? $request->search : '';
        $authors = $request->authors;

        // Сортировка по названию
        if ($term) {
            $query = $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%');
                $q->orWhereHas('user', function ($s) use ($term) {
                    $s->where('company_name', 'like', '%' . $term . '%');
                    $s->orWhereHas('author_info', function ($k) use ($term) {
                        $k->where('name', 'like', '%' . $term . '%');
                        $k->orWhere('surname', 'like', '%' . $term . '%');
                    });
                });
            });
        }
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
            $query->withCount(['course_members' => function ($q) {
                $q->where('student_course.is_finished', '=', true);
                $q->whereIn('paid_status', [1, 2]);
            }])->having('course_members_count', '>=', $members_count);
        }
        // Сортировка по профессиям
        if ($specialities) {
            if (count(array_filter($specialities)) > 0) {
                $query->whereHas('professions', function ($q) use ($request) {
                    $q->whereIn('professions.id', $request->specialities);
                });
            }

            $professions = Professions::whereIn('id', $specialities)->get();
        }
        // Сортировка по навыкам
        if ($skills) {
            if (count(array_filter($skills)) > 0) {
                $query->whereHas('skills', function ($q) use ($request) {
                    $q->whereIn('skills.id', $request->skills);
                });
            }
            $skills = Skill::whereIn('id', $skills)->get();
        }
        // Сортировка по авторам
        if ($authors) {
            $authors = User::whereIn('id', $authors)->get();

            $query->whereHas('users', function ($q) use ($request) {
                $q->whereIn('users.id', $request->authors);
            });
        }

        $items = $query->paginate(6);
        // Контент
        $content = Page::wherePageAlias('course_catalog')->first();
        return view("app.pages.general.courses.catalog.course_catalog", [
            "items" => $items,
            "request" => $request,
            "professions" => $professions ?? null,
            "skills" => $skills ?? null,
            "authors" => $authors ?? null,
            "content" => $content
        ]);
    }

    public function courseView($lang, Course $item)
    {
        if ($item->status == Course::published) {

            $courses = $item->user->courses()->get();
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

            $themes = $item->themes()->orderBy('index_number', 'asc')->get();
            $lessons_count = count(Lesson::where('course_id', '=', $item->id)->get());

            $lessons = $item->lessons;
            $videos_count = [];
            $audios_count = [];
            $attachments_count = [];

            $course_rates = CourseRate::where('course_id', '=', $item->id)->paginate(5);

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

            if (Auth::check()) {
                $student_course = StudentCourse::where('student_id', '=', Auth::user()->id)->where('course_id', '=', $item->id)->first();
                $student_rate = CourseRate::where('student_id', '=', Auth::user()->id)->where('course_id', '=', $item->id)->first();
            }
            $coursework = $item->lessons->where('type', '=', 3)->first();
            $final_test = $item->lessons->where('type', '=', 4)->first();

            return view("app.pages.general.courses.catalog.course_view", [
                "item" => $item,
                "themes" => $themes,
                "rates" => $rates,
                "author_students" => $author_students,
                "courses" => $courses,
                "author_students_finished" => $author_students_finished,
                "average_rates" => $average_rates,
                "lessons_count" => $lessons_count,
                "student_course" => $student_course ?? [],
                "student_rate" => $student_rate ?? [],
                "coursework" => $coursework,
                "final_test" => $final_test,
                "videos_count" => array_sum($videos_count),
                "audios_count" => array_sum($audios_count),
                "attachments_count" => array_sum($attachments_count),
                'course_rates' => $course_rates
            ]);
        } else {
            return redirect("/" . app()->getLocale() . "/course-catalog");
        }
    }

    public function getProfessionsByName(Request $request, $lang)
    {

        $profession_name = $request->name ?? '';
        $page = $request->page ?? 1;

        $professions = Professions::where('name_' . $lang, 'like', '%' . $profession_name . '%')
            ->where('parent_id', '!=', null)
            ->orderBy('name_' . $lang, 'asc')
            ->paginate(50, ['*'], 'page', $page);

        return $professions;
    }

    public function getSkillsByData(Request $request, $lang)
    {
        $professions = $request->professions;
        $skill_name = $request->name ?? '';
        $page = $request->page ?? 1;

        if ($professions != []) {
            $page = $request->page ?? 1;

            $professions_group = Professions::whereIn('id', $professions)->pluck('parent_id');
            $skills = Skill::whereHas('group_professions', function ($q) use ($professions_group) {
                $q->whereIn('profession_skills.profession_id', $professions_group);
            })->paginate(50, ['*'], 'page', $page);

            return $skills;
        } else {
            $skills = Skill::where('name_' . $lang, 'like', '%' . $skill_name . '%')
                ->orderBy('name_' . $lang, 'asc')
                ->paginate(50, ['*'], 'page', $page);
        }

        return $skills;
    }

    public function getSkills(Request $request, $lang)
    {
        $skill_name = $request->name ?? '';
        $page = $request->page ?? 1;

        $skills = Skill::where('name_' . $lang, 'like', '%' . $skill_name . '%')
            ->orderBy('name_' . $lang, 'asc')
            ->paginate(50, ['*'], 'page', $page);

        return $skills;
    }

    public function getAuthorsByName(Request $request, $lang)
    {
        $author_name = $request->name ?? '';
        $page = $request->page ?? 1;

        $authors = User::with('author_info')->whereHas('author_info', function ($q) use ($author_name) {
            $q->where('name', 'like', '%' . $author_name . '%')->orWhere('surname', 'like', '%' . $author_name . '%');
        })->paginate(50, ['*'], 'page', $page);

        foreach ($authors as $author) {
            $author->name = $author->author_info->name . ' ' . $author->author_info->surname;
        }

        return $authors;
    }

    public function getCourseSkills($lang, Request $request)
    {

        $skill_name = $request->name ?? '';
        $page = $request->page ?? 1;

        $skills = Skill::where('name_' . $lang, 'like', '%' . $skill_name . '%')
            ->orderBy('name_' . $lang, 'asc')
            ->paginate(50, ['*'], 'page', $page);

        return $skills;
    }

    public function getProfessionsBySkills($lang, Request $request)
    {

        $skill_id = $request->skill_id;
        $page = $request->page ?? 1;

        $professions_group = Professions::whereHas('skills', function ($q) use ($skill_id) {
            $q->where('skills.id', '=', $skill_id);
        })->pluck('id');
        $professions = Professions::whereIn('parent_id', $professions_group)->paginate(50, ['*'], 'page', $page);

        return $professions;

    }

}
