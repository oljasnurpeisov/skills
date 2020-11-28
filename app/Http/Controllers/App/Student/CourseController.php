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
            'rate' => 'required|min:1|max:1',
            'rate_description' => 'required|max:255',
        ]);

        $item = new CourseRate;
        $item->course_id = $course->id;
        $item->student_id = Auth::user()->id;
        $item->rate = $request->rate;
        $item->description = $request->rate_description;
        $item->save();

        return redirect('/' . $lang . '/student/my-courses');
    }

    public function studentCourses(Request $request, $lang)
    {
        return redirect("/", 302);
        // Получить все курсы обучающегося
        $query = StudentCourse::where('student_id', '=', Auth::user()->id)->whereHas('course', function ($q) {
            $q->where('status', '=', Course::published);
        });
        $items = $query->paginate();

        foreach ($items as $key => $item) {
            // Получить все темы из курса
            $themes = $item->course->themes()->orderBy('index_number', 'asc')->get();
            $lessons_count = 0;
            $finished_lessons_count = 0;
            foreach ($themes as $theme) {
                foreach ($theme->lessons()->get() as $lesson) {
                    // Инкремент общего количества уроков
                    $lessons_count++;
                    // Инкремент выполненых уроков
                    $confirm_lessons = StudentLesson::where('lesson_id', '=', $lesson->id)->where('is_finished', '=', true)->first();
                    if (!empty($confirm_lessons)) {
                        $finished_lessons_count++;
                    }
                }
            }
            // Добавление новых полей в коллекцию
            $item->lessons_count = $lessons_count;
            $item->finished_lessons_count = $finished_lessons_count;
        }

        return view("app.pages.student.courses.my_courses", [
            "items" => $items
        ]);
    }
}
