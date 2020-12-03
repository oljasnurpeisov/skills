<?php

namespace App\Http\Controllers\App\Student;

use App\Extensions\FormatDate;
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
use Illuminate\Support\Facades\File;


class LessonController extends Controller
{
    public function lessonView($lang, Course $course, Lesson $lesson)
    {
        $theme = $lesson->themes;

        $time = FormatDate::convertMunitesToTime($lesson->duration);

        $return_view = view("app.pages.student.lesson.view", [
            "item" => $course,
            "lesson" => $lesson,
            "theme" => $theme,
            "time" => $time
        ]);


        if (!empty($lesson->lesson_student)) {
            // Если доступ к курсу есть
            if ($lesson->lesson_student->is_access == true) {
                return $return_view;
                // Если доступа нет, вернуться обратно
            } else {
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
            }
        } else {
            // Если все уроки не доступны сразу
            if ($course->is_access_all == false) {
                if ($theme) {
                    // Получить первый урок и первую тему из курса
                    $first_theme = Theme::where('course_id', '=', $course->id)->orderBy('index_number', 'asc')->first();
                    $first_lesson = Lesson::where('theme_id', '=', $theme->id)->orderBy('index_number', 'asc')->first();

                    // Проверить является ли урок первым в курсе
                    if (($theme->id == $first_theme->id) and ($first_lesson->id == $lesson->id)) {

                        $this->syncUserLessons($lesson->id);

                        return $return_view;
                        // Если урок не является первым в курсе
                    } else {
                        // Получить предыдущую тему и урок из этой темы
                        $previous_theme = Theme::where('course_id', '=', $course->id)->where('index_number', '<', $theme->index_number)->first();
                        $previous_lesson_theme = Lesson::where('index_number', '<', $lesson->index_number)->where('theme_id', '=', $theme->id)->first();
                        // Если предыдущая тема есть, то получить урок из предыдущей темы
                        if (!empty($previous_theme)) {
                            $previous_lesson = Lesson::where('theme_id', '=', $previous_theme->id)->orderBy('index_number', 'desc')->first();
                        }
                        // Если есть урок из предыдущей темы и он завершен, дать доступ к текущему уроку
                        if (!empty($previous_lesson_theme)) {
                            if (!empty($previous_lesson_theme->lesson_student->is_finished) == true) {
                                $this->syncUserLessons($lesson->id);

                                return $return_view;
                                // Если урок из предыдущей темы не завершен, вернуться обратно
                            } else {
                                return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
                            }
                        } else {
                            // Если есть урок и он завершен, дать доступ к текущему уроку
                            if (!empty($previous_lesson) and !empty($previous_lesson->lesson_student->is_finished) == true) {
                                $this->syncUserLessons($lesson->id);

                                return $return_view;
                                // Если урок не завершен, вернуться обратно
                            } else {
                                return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
                            }
                        }
                    }
                } else {
                    // Проверить завершенность уроков
                    $all_course_lessons = $course->lessons()->whereNotIn('type', [3, 4])->pluck('id')->toArray();
                    $finished_lessons = Auth::user()->student_lesson()->where('course_id', '=', $course->id)->where('is_finished', '=', true)->pluck('lesson_id')->toArray();
                    // Если все курсы завершены
                    if (array_diff($all_course_lessons, $finished_lessons) == []) {
                        // Если это курсовая работа, дать доступ
                        if ($lesson->type == 3) {
                            $this->syncUserLessons($lesson->id);
                            // Если это финальный тест, проверить завершена ли курсовая
                        } else if ($lesson->type == 4) {
                            $coursework = $course->lessons()->where('type', '=', 3)->first();
                            if ($coursework) {
                                // Если есть курсовая и она завершена, дать доступ
                                if ($coursework->lesson_student->is_finished == true) {
                                    $this->syncUserLessons($lesson->id);
                                    // Если есть курсовая, но она не завершена, вернуть обратно
                                } else {
                                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
                                }
                                // Если есть курсовой нет, то дать доступ
                            } else {
                                $this->syncUserLessons($lesson->id);
                            }
                        }

                        return $return_view;
                    } else {
                        return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
                    }

                }
                // Если все уроки доступны сразу
            } else {
                $this->syncUserLessons($lesson->id);

                return $return_view;
            }
        }


    }

    public function lessonFinished($lang, Request $request, Course $course, Lesson $lesson)
    {
        switch ($request->input('action')) {
            // Переход к следующему уроку
            case 'next_lesson':
                // Пометить урок как прочитанный
                $lesson->lesson_student->is_finished = true;
                $lesson->lesson_student->save();
                // Переход к следующему уроку
                return $this->nextLessonShow($lang, $course, $lesson);
                break;
            // Переход к домашнему заданию
            case 'homework':
            case 'coursework':
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/homework');
                break;
//            case 'coursework':
//                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/coursework');
//                break;
        }

        return redirect()->back();
    }

    public function homeworkView($lang, Course $course, Theme $theme, Lesson $lesson)
    {

        if (!empty($lesson->lesson_student)) {
            if ($lesson->lesson_student->is_access == true) {
                return view("app.pages.student.lesson.homework_view", [
                    "item" => $course,
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
                return view("app.pages.student.lesson.coursework_view", [
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

    public function answerSend($lang, Request $request, Course $course, Lesson $lesson)
    {
        if (!empty($lesson->lesson_student)) {
            if ($lesson->lesson_student->is_access == true) {

                $request->validate([
                    'answer' => 'required',
                    'another_files' => 'required',
                ]);

                $answer = new StudentLessonAnswer;
                $answer->student_lesson_id = $lesson->lesson_student->id;
                $answer->student_id = Auth::user()->id;
                $answer->lesson_id = $lesson->id;
                switch ($request->input('action')) {
                    case('homework'):
                    case('coursework'):
                        $answer->type = 1;

                        // Видео с устройства
                        if (($request->videos != $answer->videos)) {
                            File::delete(public_path($answer->videos));

                            $answer->videos = $request->videos;
                        }
                        // Аудио с устройства
                        if (($request->audios != $answer->audios)) {
                            File::delete(public_path($answer->audios));

                            $answer->audios = $request->audios;
                        }
                        // Другие материалы
                        if (($request->another_files != $answer->another_files)) {
                            File::delete(public_path($answer->another_files));

                            $answer->another_files = $request->another_files;
                        }
                        break;

                    case('test'):
                    case('final_test'):
                        $answer->type = 0;
                        break;
                }

                $answer->answer = $request->answer;

                $answer->save();

                // Пометить урок как прочитанный
                $lesson->lesson_student->is_finished = true;
                $lesson->lesson_student->save();

                if ($lesson->type == 3) {

                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('status', __('default.pages.lessons.coursework_send_success'));
                } else if ($lesson->type == 4) {

                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('status', __('default.pages.lessons.coursework_send_success'));
                } else {
                    // Вернуть следующий урок
                    return $this->nextLessonShow($lang, $course, $lesson);
                }

            } else {
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
            }
        } else {
            return redirect('/' . $lang . '/course-catalog')->with('error', __('default.pages.lessons.access_denied_message'));
        }
    }

    public function nextLessonShow($lang, $course, $lesson)
    {
        $course_status = StudentCourse::where('course_id', '=', $course->id)->first();
        if ($course_status->is_finished == false) {
            // Получить следующий урок
            $theme = $lesson->themes;
            $next_lesson_theme = Lesson::where('index_number', '>', $lesson->index_number)->where('theme_id', '=', $theme->id)->first();
            $next_theme = Theme::where('course_id', '=', $course->id)->where('index_number', '>', $theme->index_number)->first();
            if (!empty($next_theme)) {
                $next_lesson = Lesson::where('theme_id', '=', $next_theme->id)->orderBy('index_number', 'asc')->first();
            }

            // Переход к следующему уроку

            if (!empty($next_lesson_theme)) {
                // Установка доступа к следующему уроку
                $this->syncUserLessons($next_lesson_theme->id);
                // Проверить окончание курса
                $this->finishedCourse($course);
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $next_lesson_theme->id);

            } else {
                if (!empty($next_lesson) and !empty($next_lesson->lesson_student->is_finished) == false) {
                    // Установка доступа к следующему уроку
                    $this->syncUserLessons($next_lesson->id);
                    // Проверить окончание курса
                    $this->finishedCourse($course);
                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $next_lesson->id);

                    // Если следующего урока нет
                } else {
                    $coursework = $course->lessons()->where('type', '=', 3)->first();
                    $final_test = $course->lessons->where('type', '=', 4)->first();
                    $coursework_item = StudentLesson::where('lesson_id', '=', $coursework->id)->where('student_id', '=', Auth::user()->id)->first();
                    $final_test_item = StudentLesson::where('lesson_id', '=', $final_test->id)->where('student_id', '=', Auth::user()->id)->first();

                    if (!empty($coursework) and ($coursework->id != $lesson->id)) {
                        $this->syncUserLessons($coursework->id);
                    } else if (!empty($final_test) and empty($coursework) and ($final_test->id != $lesson->id)) {
                        $this->syncUserLessons($final_test->id);

                    }
                    if (!empty($final_test) and ($final_test->id != $lesson->id) and !empty($coursework)) {
                        if ($coursework->is_finished == true) {
                            $this->syncUserLessons($final_test->id);
                        }

                    }
                    // Проверить окончание курса
                    $this->finishedCourse($course);
                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id);
                }

            }

        } else {
            return redirect('/' . $lang . '/course-catalog/course/' . $course->id);
        }
    }

    public function finishedCourse($course)
    {
        // Получить все уроки данного курса
        $lessons = array();
        foreach ($course->lessons as $lesson) {
            array_push($lessons, $lesson->id);
        }
        // Получить все незавершенные уроки данного курса
        $student_unfinished_lessons = Lesson::whereHas('student_lessons', function ($q) {
            $q->where('student_lesson.is_finished', '=', false);
        })->whereIn('id', $lessons)->get();
        // Если незавершенных уроков нет, изменить статус курса как завершенный
        if (count($student_unfinished_lessons) == 0) {
            $student_course = StudentCourse::where('student_id', '=', Auth::user()->id)->where('course_id', '=', $course->id)->first();
            $student_course->is_finished = true;
            $student_course->save();
        }
    }

    public function syncUserLessons(int $lesson_id)
    {
        $item = StudentLesson::where('lesson_id', '=', $lesson_id)->where('student_id', '=', Auth::user()->id)->first();
        if (empty($item)) {
            $item = new StudentLesson;
            $item->lesson_id = $lesson_id;
            $item->student_id = Auth::user()->id;
            $item->is_access = true;
            $item->save();
        }

    }
}
