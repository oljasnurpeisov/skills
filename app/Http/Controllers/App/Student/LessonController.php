<?php

namespace App\Http\Controllers\App\Student;

use App\Extensions\FormatDate;
use App\Extensions\NotificationsHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\PaymentHistory;
use App\Models\StudentCertificate;
use App\Models\StudentCourse;
use App\Models\StudentLesson;
use App\Models\StudentLessonAnswer;
use App\Models\Theme;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PDF;

class LessonController extends Controller
{
    public function lessonView($lang, Course $course, Lesson $lesson)
    {
        $lesson_student = StudentLesson::whereLessonId($lesson->id)->whereStudentId(Auth::user()->id)->first();

        $theme = $lesson->themes;

        $time = FormatDate::convertMunitesToTime($lesson->duration);

        $return_view = view("app.pages.student.lesson.view", [
            "item" => $course,
            "lesson" => $lesson,
            "theme" => $theme,
            "time" => $time
        ]);

        if (!empty($lesson_student)) {
            // Если доступ к курсу есть
            if ($lesson_student->is_access == true) {
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
                    } else {
                        // Если урок не является первым в курсе
                        // Получить предыдущую тему и урок из этой темы
                        $previous_theme = Theme::where('course_id', '=', $course->id)
                            ->where('index_number', '<', $theme->index_number)
                            ->orderBy('index_number', 'desc')
                            ->first();
                        $previous_lesson_theme = Lesson::where('index_number', '<', $lesson->index_number)
                            ->where('theme_id', '=', $theme->id)
                            ->orderBy('index_number', 'desc')
                            ->first();
                        // Если предыдущая тема есть, то получить урок из предыдущей темы
                        if (!empty($previous_theme)) {
                            $previous_lesson = Lesson::where('theme_id', '=', $previous_theme->id)->orderBy('index_number', 'desc')->first();
                        }
                        // Если есть урок из предыдущей темы и он завершен, дать доступ к текущему уроку
                        if (!empty($previous_lesson_theme)) {
                            if (!empty($previous_lesson_theme->lesson_student) && !empty($previous_lesson_theme->lesson_student->whereStudentId(Auth::user()->id)->is_finished) == true) {
                                $this->syncUserLessons($lesson->id);

                                return $return_view;
                                // Если урок из предыдущей темы не завершен, вернуться обратно
                            } else {
                                return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
                            }
                        } else {
                            // Если есть урок и он завершен, дать доступ к текущему уроку
                            if (!empty($previous_lesson) and !empty($previous_lesson->whereStudentId(Auth::user()->id)->is_finished) == true) {
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
                                if (!empty($coursework->lesson_student->whereStudentId(Auth::user()->id)->first()->is_finished) == true) {
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
                // Проверить завершенность уроков
                $all_course_lessons = $course->lessons()->whereNotIn('type', [3, 4])->pluck('id')->toArray();
                $finished_lessons = Auth::user()->student_lesson()->where('course_id', '=', $course->id)->where('is_finished', '=', true)->pluck('lesson_id')->toArray();
                switch ($lesson->type) {
                    case (3):
                        if (array_diff($all_course_lessons, $finished_lessons) == []) {
                            $this->syncUserLessons($lesson->id);
                        } else {
                            return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
                        }
                        break;
                    case (4):
                        $coursework = $course->lessons()->where('type', '=', 3)->first();
                        if ($coursework) {
                            // Если есть курсовая и она завершена, дать доступ
                            if (!empty($coursework->lesson_student->whereStudentId(Auth::user()->id)->first()->is_finished) == true) {
                                $this->syncUserLessons($lesson->id);
                                // Если есть курсовая, но она не завершена, вернуть обратно
                            } else {
                                return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
                            }
                            // Если есть курсовой нет, то дать доступ
                        } else {
                            $this->syncUserLessons($lesson->id);
                        }
                        break;
                    default:
                        $this->syncUserLessons($lesson->id);
                        break;
                }

                return $return_view;
            }
        }

    }

    public function lessonFinished($lang, Request $request, Course $course, Lesson $lesson)
    {
        $lesson_student = StudentLesson::whereLessonId($lesson->id)->whereStudentId(Auth::user()->id)->first();
        switch ($request->input('action')) {
            // Переход к следующему уроку
            case 'next_lesson':
                // Пометить урок как прочитанный
                $lesson_student->is_finished = true;
                $lesson_student->save();
                // Переход к следующему уроку
                return $this->nextLessonShow($lang, $course, $lesson);
                break;
            // Переход к домашнему заданию
            case 'homework':
            case 'coursework':
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/homework');
                break;
            case 'test':
            case 'final-test':
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/test');
                break;
        }

        return redirect()->back();
    }

    public function homeworkView($lang, Course $course, Theme $theme, Lesson $lesson)
    {
        $lesson_student = StudentLesson::whereLessonId($lesson->id)->whereStudentId(Auth::user()->id)->first();
        if (!empty($lesson_student)) {
            if ($lesson_student->is_access == true) {
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

    public function testView($lang, Course $course, Lesson $lesson)
    {
        $lesson_student = StudentLesson::whereLessonId($lesson->id)->whereStudentId(Auth::user()->id)->first();
        if (!empty($lesson_student)) {
            if ($lesson_student->is_finished != true) {
                if ($lesson_student->is_access == true) {
                    return view("app.pages.student.lesson.test_view_lesson", [
                        "item" => $course,
                        "lesson" => $lesson
                    ]);
                } else {
                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
                }
            } else {
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.test_finished_warning'));
            }
        } else {
            return redirect('/' . $lang . '/course-catalog')->with('error', __('default.pages.lessons.access_denied_message'));
        }
    }

    public function answerSend($lang, Request $request, Course $course, Lesson $lesson)
    {
        $lesson_student = StudentLesson::whereLessonId($lesson->id)->whereStudentId(Auth::user()->id)->first();
        if (!empty($lesson_student)) {
            if ($lesson_student->is_access == true) {

                if ($lesson->type == 3) {
                    $request->validate([
                        'answer' => 'required',
                        'another_files' => 'required|not_in:[]',
                    ]);
                }

                $result = StudentLessonAnswer::where('student_id', '=', Auth::user()->id)
                    ->where('lesson_id', '=', $lesson->id)->first();

                if (!$result) {
                    $answer = new StudentLessonAnswer;
                } else {
                    $answer = $result;
                }
                $answer->student_lesson_id = $lesson_student->id;
                $answer->student_id = Auth::user()->id;
                $answer->lesson_id = $lesson->id;
                switch ($request->input('action')) {
                    case('homework'):
                    case('coursework'):
                        $answer->type = 1;

                        $answer->answer = $request->answer;

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

                        // Пометить урок как законченный
                        $lesson_student->is_finished = true;
                        $lesson_student->save();

                        break;

                    case('test'):
                    case('final_test'):
                        $answer->type = 0;

                        $answer->answer = json_encode($request->answers);

                        break;
                }

                $answer->save();

                if ($lesson->type == 3) {
                    $this->finishedCourse($course);
                    // Подтвердить квалификацию
                    $student_course = StudentCourse::where('student_id', '=', Auth::user()->id)->where('course_id', '=', $course->id)->first();
                    $student_course->is_qualificated = true;
                    $student_course->save();
                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('status', __('default.pages.lessons.coursework_send_success'));
                } else if ($lesson->type == 4) {
                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/test-result');
                } else if (($lesson->type == 2) and ($lesson->end_lesson_type == 0)) {
                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/test-result');
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

    public function testResultView($lang, Request $request, Course $course, Lesson $lesson)
    {
        $lesson_student = StudentLesson::whereLessonId($lesson->id)->whereStudentId(Auth::user()->id)->first();

        $result = StudentLessonAnswer::where('student_id', '=', Auth::user()->id)
            ->where('lesson_id', '=', $lesson->id)->first();
        if ($result) {

            $right_answers = [];

            foreach (json_decode($lesson->practice)->questions as $key => $question) {
                $right_answers[] = $question->answers[0];
            }

            $answers = json_decode($result->answer);

            $test_results = array_diff($answers, $right_answers);

            $right_answers = 0;

            foreach (json_decode($lesson->practice)->questions as $key => $question) {
                if (!array_key_exists($key, $test_results)) {
                    $right_answers++;
                }
            }

            // Если кол-во правильных ответов достаточно
            if ($right_answers >= json_decode($lesson->practice)->passingScore) {
                // Пометить урок как законченный
                $lesson_student->is_finished = true;
                $lesson_student->save();

                $this->finishedCourse($course);
            }


            return view("app.pages.student.lesson.test_results_view", [
                "item" => $course,
                "lesson" => $lesson,
                "results" => $test_results,
                "right_answers" => $right_answers
            ]);
        } else {
            return abort(404);
        }
    }

    public function nextLessonShow($lang, $course, $lesson)
    {
        $lesson_student = StudentLesson::whereLessonId($lesson->id)->whereStudentId(Auth::user()->id)->first();
        $course_status = StudentCourse::where('course_id', '=', $course->id)->first();

        if ($course_status->is_finished == false) {
            // Получить следующий урок
            $theme = $lesson->themes;
            $next_lesson_theme = Lesson::where('index_number', '>', $lesson->index_number)
                ->where('theme_id', '=', $theme->id)
                ->orderBy('index_number', 'asc')
                ->first();
            $next_theme = Theme::where('course_id', '=', $course->id)
                ->where('index_number', '>', $theme->index_number)
                ->orderBy('index_number', 'asc')
                ->first();
            if (!empty($next_theme)) {
                $next_lesson = Lesson::where('theme_id', '=', $next_theme->id)
                    ->orderBy('index_number', 'asc')
                    ->first();
            }

            // Переход к следующему уроку

            if (!empty($next_lesson_theme)) {
                // Установка доступа к следующему уроку
                $this->syncUserLessons($next_lesson_theme->id);
                // Проверить окончание курса
                $this->finishedCourse($course);
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $next_lesson_theme->id);

            } else {
                if (!empty($next_lesson) and !empty($next_lesson->whereStudentId(Auth::user()->id)->is_finished) == false) {
                    // Установка доступа к следующему уроку
                    $this->syncUserLessons($next_lesson->id);
                    // Проверить окончание курса
                    $this->finishedCourse($course);
                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $next_lesson->id);

                    // Если следующего урока нет
                } else {
                    $coursework = $course->lessons()->where('type', '=', 3)->first();
                    $final_test = $course->lessons->where('type', '=', 4)->first();

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
        $lessons = $course->lessons->pluck('id')->toArray();

        // Получить все завершенные уроки данного курса
        $student_finished_lessons = Lesson::whereHas('student_lessons', function ($q) {
            $q->where('student_lesson.is_finished', '=', true);
        })->whereIn('id', $lessons)->get();

        // Если кол-во уроков и кол-во завершенных уроков равно, то отметить курс как завершенный
        if ($course->lessons->count() == $student_finished_lessons->count()) {
            $student_course = StudentCourse::where('student_id', '=', Auth::user()->id)->where('course_id', '=', $course->id)->first();
            if ($student_course->is_finished == false) {
                $student_course->is_finished = true;
                $student_course->save();

                // Сохранить сертификат
                $user_certificate = StudentCertificate::where('user_id', '=', Auth::user()->id)
                    ->where('course_id', '=', $course->id)->first();
                if (empty($user_certificate)) {
                    $this->saveCertificates($course, $student_course);
                }
                // Присвоить обучающемуся полученные навыки
                Auth::user()->skills()->sync($course->skills->pluck('id')->toArray(), false);
                // Отправить уведомление
                $notification_name = "notifications.course_student_finished";
                NotificationsHelper::createNotification($notification_name, $course->id, Auth::user()->id);
            }
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

    public function saveCertificates($course, $student_course)
    {
        $languages = ["ru", "kk"];

        $certificate = new StudentCertificate;
        $certificate->user_id = Auth::user()->id;
        $certificate->course_id = $course->id;
        $certificate->save();

        foreach ($languages as $language) {
            $data = [
                'author_name' => $course->user->company_name,
                'student_name' => Auth::user()->student_info->name,
                'duration' => $course->lessons->sum('duration'),
                'course_name' => $course->name,
                'skills' => $course->skills,
                'certificate_id' => sprintf("%012d", $certificate->id) . '-' . date('dmY')
            ];
            $pdf = PDF::loadView('app.pages.page.pdf.certificate_' . $course->certificate_id . '_' . $language, ['data' => $data]);
            $pdf = $pdf->setPaper('a4', 'portrait');

            $path = public_path('users/user_' . Auth::user()->id . '');
            $pdf->save($path . '/' . 'course_' . $course->id . '_certificate_' . $language . '.pdf');

            $pdf = new \Spatie\PdfToImage\Pdf($path . '/' . 'course_' . $course->id . '_certificate_' . $language . '.pdf');
            $pdf->saveImage($path . '/' . 'course_' . $course->id . '_image_' . $language . '.png');
        }

        $file_path = '/users/user_' . Auth::user()->id . '';

        $certificate->pdf_ru = $file_path . '/' . 'course_' . $course->id . '_certificate_' . $languages[0] . '.pdf';
        $certificate->pdf_kk = $file_path . '/' . 'course_' . $course->id . '_certificate_' . $languages[1] . '.pdf';
        $certificate->png_ru = $file_path . '/' . 'course_' . $course->id . '_image_' . $languages[0] . '.png';
        $certificate->png_kk = $file_path . '/' . 'course_' . $course->id . '_image_' . $languages[1] . '.png';

        $certificate->save();

        $cert = base64_encode(file_get_contents(env('APP_URL') . $certificate->png_ru));
        $this->putNewSkills(Auth::user()->student_info->uid, $course, $cert);

    }

    public function putNewSkills($uid, $course, $cert)
    {
        $data = [
            'uid' => $uid,
            'course' => [
                'id' => $course->id,
                'name' => $course->name,
                'skills' => $course->skills->pluck('code_skill')->toArray()
            ],
            'cert' => $cert
        ];

        $client = new Client(['verify' => false]);

        try {
            $body = $data;
            $response = $client->request('PUT', 'https://btest.enbek.kz/ru/api/put-navyk-from-obuch', [
                'body' => json_encode($body),
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);
        } catch (BadResponseException $e) {
            return $e;
        }

        return $data;
    }
}
