<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\PaymentHistory;
use App\Models\StudentCourse;
use App\Models\Theme;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LessonController extends Controller
{
    public function lessonView($lang, Course $course, Theme $theme, Lesson $lesson)
    {
        if($lesson->lesson_student->is_access == true){
            return view("app.pages.general.lesson.view", [
                "course" => $course,
                "lesson" => $lesson,
                "theme" => $theme
            ]);
        }else{
            return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
        }


    }

    public function lessonFinished($lang, Request $request, Course $course, Theme $theme, Lesson $lesson)
    {
        switch ($request->input('action')) {
            case 'next_lesson':

                // Получить следующий урок
                $next_lesson = Lesson::where('id', '>', $lesson->id)->whereHas('themes', function ($q) use ($theme) {
                    $q->where('themes.id', '=', $theme->id);
                })->first();
                // Пометить урок как прочитанный
                $lesson->lesson_student->is_finished = true;
                $lesson->lesson_student->save();
                // Переход к следующему уроку
                if (!empty($next_lesson)) {
                    // Установка доступа к следующему уроку
                    $next_lesson->lesson_student->is_access = true;
                    $next_lesson->lesson_student->save();

                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/theme-' . $theme->id . '/lesson-' . $next_lesson->id);
                    // Если следующего урока нет
                } else {
                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id);
                }
        }

        return redirect()->back();
    }
}
