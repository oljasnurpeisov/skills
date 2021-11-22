<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRate;
use App\Models\ProfessionalArea;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorsController extends Controller
{
    public function authorsCatalog(Request $request, $lang)
    {
        $term = $request->search ? $request->search : '';
        $query = User::whereHas('roles', function ($q) {
            $q->whereSlug('author');
        });
        if ($term) {
            $query = $query->where('company_name', 'like', '%' . $term . '%');
        }
        $items = $query->paginate(8);
        foreach ($items as $item) {
            $courses = $item->courses()->get();
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
            // Оценка автора исходя из всех оценок
            if (count($rates) == 0) {
                $average_rates = 0;
            } else {
                $average_rates = array_sum($rates) / count($rates);
            }

            $item->rates = $rates;
            $item->courses = $courses;
            $item->author_students = $author_students;
            $item->average_rates = $average_rates;
        }
        return view("app.pages.general.authors.authors_catalog", [
            "items" => $items,
            'request' => $request,
        ]);
    }

    public function authorView($lang, User $user, Request $request)
    {
        $courses = $user->courses()->get();
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

        // Курсы автора с фильтром.
        $query = Course::where('status', '=', Course::published)
        ->where('author_id', '=', $user->id);

        $lang_ru = $request->lang_ru ?? null;
        $lang_kk = $request->lang_kk ?? null;
        $course_type = $request->course_type ?? '';
        $course_sort = $request->course_sort ?? '';
        $min_rating = $request->min_rating ?? 0;
        $members_count = $request->members_count ?? 0;
        $specialities = $request->specialities;
        $skills = $request->skills;
        $professional_areas = $request->professional_areas;
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
        // Получить проф.области
        if ($professional_areas) {
            if (count(array_filter($professional_areas)) > 0) {
                $query->whereHas('professional_areas', function ($q) use ($request) {
                    $q->whereIn('professional_areas.id', $request->professional_areas);
                });
            }

            $professional_areas = ProfessionalArea::whereIn('id', $professional_areas)->get();
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

        $courseItems = $query->paginate(6);
        return view("app.pages.general.authors.author_view", [
            'user' => $user,
            'request' => $request,
            "rates" => $rates,
            "author_students" => $author_students,
            "courses" => $courses,
            "courseItems" => $courseItems,
            "author_students_finished" => $author_students_finished,
            "average_rates" => $average_rates,
            "professional_areas" => $professional_areas ?? null,
            "professions" => $professions ?? null,
            "skills" => $skills ?? null,
        ]);
    }
}
