<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function authorsReports()
    {

        $items = User::whereHas('roles', function ($q) {
            $q->whereSlug('author');
        })->where('email_verified_at', '!=', null)->paginate(10);

        foreach ($items as $item) {
            $rates_array = [];
            $author_students = [];
            $author_students_finished = [];
            $author_students_finished_courseWork = [];
            foreach ($item->courses as $course) {
                // Количество отзывов
                foreach ($course->rate as $rate) {
                    $rates_array[] = $rate->rate;
                }
                // Количество обучающихся во всех курсах автора
                foreach ($course->course_members as $member) {
                    $author_students[$member['student_id']][] = $member;
                    $author_students_finished[$member['student_id']][] = $member->where('is_finished', '=', true);
                }
                if ($course->courseWork()) {
                    $author_students_finished_courseWork[] = $course->courseWork()->finishedLesson()->toArray();
                }
            }
            // Оценка автора исходя из всех оценок
            if (count($rates_array) == 0) {
                $item->average_rates = 0;
            } else {
                $item->average_rates = array_sum($rates_array) / count($rates_array);
            }
            // Количество уникальных обучающихся
            $item->members = $author_students;
            // Количество сертифицированных
            $item->certificate_members = $author_students_finished;
            // Количество подтвердивших квалификацию
            $item->qualification_students = array_filter($author_students_finished_courseWork);
        }

        return view('admin.v2.pages.reports.authors_report', [
            'items' => $items

        ]);
    }

    public function coursesReports()
    {
        $items = Course::paginate(10);

        return view('admin.v2.pages.reports.courses_report', [
            'items' => $items
        ]);
    }

    public function studentsReports()
    {
        $items = User::whereHas('roles', function ($q) {
            $q->whereSlug('student');
        })->paginate(10);
        $i = [];
        foreach ($items as $item) {
            $finishedCourseWorks = 0;
            foreach ($item->student_course->whereIn('paid_status', [1, 2]) as $course) {
                if ($course->course->courseWork()) {
                    if ($item->student_lesson->where('lesson_id', '=', $course->course->courseWork()->id)) {
                        $i[] = $course->course->courseWork();
                        $finishedCourseWorks++;
                    }
                }
            }
            // Количество законченных курсовых работ
            $item->finishedCourseWorkrs = $finishedCourseWorks;
        }

        return view('admin.v2.pages.reports.students_report', [
            'items' => $items
        ]);
    }
}
