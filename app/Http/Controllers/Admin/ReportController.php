<?php

namespace App\Http\Controllers\Admin;

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
                if($course->courseWork()){
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
//        return $author_students_finished_courseWork;
    }
}
