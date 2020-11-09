<?php

namespace App\Http\Controllers\App\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\PaymentHistory;
use App\Models\StudentCourse;
use App\Models\StudentLesson;
use App\Models\StudentLessonAnswer;
use App\Models\Theme;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LessonController extends Controller
{
    public function lessonView($lang, Course $course, Theme $theme, Lesson $lesson)
    {

        if (!empty($lesson->lesson_student)) {
            if ($lesson->lesson_student->is_access == true) {
                return view("app.pages.general.lesson.view", [
                    "course" => $course,
                    "lesson" => $lesson,
                    "theme" => $theme
                ]);
            } else {
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
            }
        } else {
            return redirect('/' . $lang . '/course-catalog')->with('error', __('default.pages.lessons.access_denied_message'));
        }


    }

    public function lessonFinished($lang, Request $request, Course $course, Theme $theme, Lesson $lesson)
    {
        switch ($request->input('action')) {
            // Переход к следующему уроку
            case 'next_lesson':
                // Пометить урок как прочитанный
                $lesson->lesson_student->is_finished = true;
                $lesson->lesson_student->save();
                // Проверить окончание курса
                $this->finishedCourse($course);
                // Переход к следующему уроку
                return $this->nextLessonShow($lang, $course, $theme, $lesson);
                break;
            // Переход к домашнему заданию
            case 'homework':
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/theme-' . $theme->id . '/lesson-' . $lesson->id . '/homework');
                break;
            case 'coursework':
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/theme-' . $theme->id . '/lesson-' . $lesson->id . '/coursework');
                break;
        }

        return redirect()->back();
    }

    public function homeworkView($lang, Course $course, Theme $theme, Lesson $lesson)
    {

        if (!empty($lesson->lesson_student)) {
            if ($lesson->lesson_student->is_access == true) {
                return view("app.pages.general.lesson.homework_view", [
                    "course" => $course,
                    "lesson" => $lesson,
                    "theme" => $theme
                ]);
            } else {
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
            }
        } else {
            return redirect('/' . $lang . '/course-catalog')->with('error', __('default.pages.lessons.access_denied_message'));
        }

    }

    public function courseworkView($lang, Course $course, Theme $theme, Lesson $lesson)
    {

        if (!empty($lesson->lesson_student)) {
            if ($lesson->lesson_student->is_access == true) {
                return view("app.pages.general.lesson.coursework_view", [
                    "course" => $course,
                    "lesson" => $lesson,
                    "theme" => $theme
                ]);
            } else {
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
            }
        } else {
            return redirect('/' . $lang . '/course-catalog')->with('error', __('default.pages.lessons.access_denied_message'));
        }

    }

    public function answerSend($lang, Request $request, Course $course, Theme $theme, Lesson $lesson)
    {
        if (!empty($lesson->lesson_student)) {
            if ($lesson->lesson_student->is_access == true) {
                $attachments = array();

                $answer = new StudentLessonAnswer;
                $answer->student_id = Auth::user()->id;
                $answer->lesson_id = $lesson->id;
                if($request->input('action') == 'homework'){
                    $answer->type = 0;
                }else{
                    $answer->type = 1;
                }
                $answer->text_answer = $request->text_answer;

                if (!empty($request->files)) {

                    foreach ($request->files as $attachment) {
                        $attachmentName = time() . '.' . $attachment->getClientOriginalExtension();
                        $attachment->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/files'), $attachmentName);
                        array_push($attachments, '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/files/' . $attachmentName);
                    }
                    $answer->attachments = json_encode($attachments);
                }

                $answer->save();

                // Пометить урок как прочитанный
                $lesson->lesson_student->is_finished = true;
                $lesson->lesson_student->save();
                // Проверить окончание курса
                $this->finishedCourse($course);
                // Вернуть следующий урок
                return $this->nextLessonShow($lang, $course, $theme, $lesson);
            } else {
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
            }
        } else {
            return redirect('/' . $lang . '/course-catalog')->with('error', __('default.pages.lessons.access_denied_message'));
        }
    }

    public function nextLessonShow($lang, $course, $theme, $lesson)
    {
        $course_status = StudentCourse::where('course_id', '=', $course->id)->first();
        if($course_status->is_finished == false) {
            // Получить следующий урок
            $next_lesson = Lesson::where('id', '>', $lesson->id)->whereHas('themes', function ($q) use ($theme) {
                $q->where('themes.id', '=', $theme->id);
            })->first();
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
        }else{
            return redirect('/' . $lang . '/course-catalog/course/' . $course->id);
        }
    }

    public function finishedCourse($course)
    {
        // Получить все темы курса
        $course_themes = Theme::whereHas('courses', function ($q) use ($course) {
            $q->where('courses.id', '=', $course->id);
        })->get();
        // Получить все уроки тем данного курса
        $lessons = array();
        foreach ($course_themes as $theme) {
            foreach ($theme->lessons as $lesson) {
                array_push($lessons, $lesson->id);
            }
        }
        // Получить все незавершенные уроки данного курса
        $student_unfinished_lessons = StudentLesson::where('student_id', '=', Auth::user()->id)->whereIn('lesson_id', $lessons)->where('is_finished', '=', false)->get();
        // Если незавершенных уроков нет, изменить статус курса как завершенный
        if (count($student_unfinished_lessons) == 0) {
            $student_course = StudentCourse::where('student_id', '=', Auth::user()->id)->where('course_id', '=', $course->id)->first();
            $student_course->is_finished = true;
            $student_course->save();
        }
    }
}
