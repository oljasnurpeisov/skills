<?php

namespace App\Http\Controllers\App\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRate;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\StudentCourse;
use App\Models\StudentLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

        return redirect('/' . $lang . '/student/my-courses');
    }

    public function studentCourses(Request $request, $lang)
    {
        // Оценка курса обучающегося
        $student_rate = CourseRate::where('student_id', '=', Auth::user()->id)->get();
        // Получить все курсы обучающегося
        $query = StudentCourse::where('student_id', '=', Auth::user()->id)->whereHas('course', function ($q) {
            $q->where('status', '=', Course::published);
        });
        $items = $query->paginate();

        foreach ($items as $key => $item) {
            $lessons_count = $item->course->lessons()->count();
            $finished_lessons_count = $item->course->lessons()->whereHas('student_lessons', function($q){
                $q->where('student_lesson.is_finished', '=', true);
            })->get()->count();

            // Добавление новых полей в коллекцию
            $item->lessons_count = $lessons_count;
            $item->finished_lessons_count = $finished_lessons_count;
        }

        return view("app.pages.student.courses.my_courses", [
            "items" => $items,
            "student_rate" => $student_rate
        ]);
    }
}
