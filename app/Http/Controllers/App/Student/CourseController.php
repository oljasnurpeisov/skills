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

    public function saveCourseRate($lang, Request $request, Course $course){
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

        return redirect('/'.$lang.'/student/my-courses');
    }

    public function studentCourses(){

        return view("app.pages.student.courses.my_courses", [

        ]);
    }
}
