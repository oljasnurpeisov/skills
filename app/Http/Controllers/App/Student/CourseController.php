<?php

namespace App\Http\Controllers\App\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRate;
use App\Models\Lesson;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\StudentCourse;
use App\Models\StudentLesson;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;


class CourseController extends Controller
{

    public function saveCourseRate($lang, Request $request, Course $course)
    {
        $request->validate([
            'rating' => 'required|min:1|max:1',
            'review' => 'required|max:255',
        ]);

        $item = new CourseRate;
        $item->course_id = $course->id;
        $item->student_id = Auth::user()->id;
        $item->rate = $request->rating;
        $item->description = $request->review;
        $item->save();

        return redirect()->back()->with('status', __('default.pages.courses.success_rate_message'));
    }

    public function studentCourses(Request $request, $lang)
    {
        $lang_ru = $request->lang_ru ?? null;
        $lang_kk = $request->lang_kk ?? null;
        $specialities = $request->specialities;
        $skills = $request->skills;
        $authors = $request->authors;
        $term = $request->search ? $request->search : '';
        $status = $request->status ?? [];
        $certificate = $request->certificate;
        $start_date_from = $request->start_date_from;
        $start_date_to = $request->start_date_to;
        $finish_date_from = $request->finish_date_from;
        $finish_date_to = $request->finish_date_to;

        // Оценка курса обучающегося
        $student_rate = CourseRate::where('student_id', '=', Auth::user()->id)->get();
        // Получить все курсы обучающегося
        $query = StudentCourse::where('student_id', '=', Auth::user()->id)->where('paid_status', '!=', 0)->whereHas('course', function ($q) {
            $q->where('status', '=', Course::published);
        });

        // Сортировка по названию
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
                            if($key === 0){
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
        if ($specialities) {
            if (count(array_filter($specialities)) > 0) {
                $query->whereHas('courses.professions', function ($q) use ($request) {
                    $q->whereIn('professions.id', $request->specialities);
                });
            }

            $professions = Professions::whereIn('id', $specialities)->get();
        }
        // Сортировка по навыкам
        if ($skills) {
            if (count(array_filter($skills)) > 0) {
                $query->whereHas('courses.skills', function ($q) use ($request) {
                    $q->whereIn('skills.id', $request->skills);
                });
            }
            $skills = Skill::whereIn('id', $skills)->get();
        }
        // Сортировка по авторам
        if ($authors) {
            $authors = User::whereIn('id', $authors)->get();

            $query->whereHas('courses.users', function ($q) use ($request) {
                $q->whereIn('users.id', $request->authors);
            });
        }
        // Сортировка по языку
        if ($lang_ru == 1 and $lang_kk == null) {
            $query = $query->whereHas('courses', function ($q) {
                $q->where('courses.lang', '=', 1);
            });
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->whereHas('courses', function ($q) {
                $q->where('courses.lang', '=', 0);
            });
        } else if ($lang_ru == null and $lang_kk == 1) {
            $query = $query->whereHas('courses', function ($q) {
                $q->whereIn('courses.lang', '=', [0, 1]);
            });
        }
        $statuses = [];
        // Сортировка по статусу
        if ($certificate) {
            $status[] = $certificate;
        }
        if ($status) {
            $query = $query->whereIn('is_finished', $status);
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

        $items = $query->paginate();

        foreach ($items as $key => $item) {
//            $lessons_count = $item->course->lessons()->count();
//            $finished_lessons_count = $item->course->lessons()->whereHas('student_lessons', function ($q) {
//                $q->where('student_lesson.is_finished', '=', true);
//            })->get()->count();

            $lessonsCount = Lesson::whereCourseId($item->course_id)
                ->whereIn('type', [1, 2])
                ->count();
            $finishedLessonsCount = Lesson::whereCourseId($item->course_id)
                ->whereIn('type', [1, 2])
                ->whereHas('student_lessons', function ($q) {
                    $q->where('student_lesson.is_finished', '=', true);
                })
                ->count();

            // Добавление новых полей в коллекцию
            $item->lessons_count = $lessonsCount;
            $item->finished_lessons_count = $finishedLessonsCount;

            if ($lessonsCount === 0) {
                $item->progress = 100;
            } else {
                $item->progress = round($finishedLessonsCount / $lessonsCount * 100);
            }
        }

        return view("app.pages.student.courses.my_courses", [
            "items" => $items,
            "student_rate" => $student_rate,
            "request" => $request,
            "skills" => $skills ?? null,
            "professions" => $professions ?? null,
            "authors" => $authors ?? null,
            "status" => $status,
            "finish_date_from" => $finish_date_from,
            "finish_date_to" => $finish_date_to,
            "start_date_from" => $start_date_from,
            "start_date_to" => $start_date_to,
        ]);
    }

}
