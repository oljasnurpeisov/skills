<?php

namespace App\Http\Controllers\App\Student;

use App\Extensions\FormatDate;
use App\Extensions\NotificationsHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Log;
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
        $theme = $lesson->themes;
        $time = FormatDate::convertMunitesToTime($lesson->duration);
        $view = view("app.pages.student.lesson.view", [
            "item" => $course,
            "lesson" => $lesson,
            "theme" => $theme,
            "time" => $time
        ]);

        $user = Auth::user();
        $studentLesson = StudentLesson::whereLessonId($lesson->id)
            ->whereStudentId($user->id)
            ->first();
        if ($studentLesson != null) {
            if ($studentLesson->is_access == true) {
                // Если доступ к уроку есть, то отображаем урок
                return $view;
            } else {
                // Если доступа к уроку нет, возвращаем на страницу курса
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id)
                    ->with('error', __('default.pages.lessons.access_denied_message'));
            }
        }

        // Если все уроки не доступны сразу
        if ($course->is_access_all == false && $theme != null) {
            // Получить первый урок и первую тему из курса
            $firstTheme = Theme::where('course_id', '=', $course->id)->orderBy('index_number', 'asc')->first();
            $firstLesson = Lesson::where('theme_id', '=', $theme->id)->orderBy('index_number', 'asc')->first();

            // Проверить является ли урок первым в курсе
            if (($theme->id == $firstTheme->id) and ($firstLesson->id == $lesson->id)) {
                $this->syncUserLessons($lesson->id);
                return $view;
            }

            // Если урок не является первым в курсе, получить предыдущий урок из этой темы
            $previousLesson = Lesson::where('index_number', '<', $lesson->index_number)
                ->where('theme_id', '=', $theme->id)
                ->orderBy('index_number', 'desc')
                ->first();
            if ($previousLesson == null) {
                // Если урока нет, получить предыдущую тему и урок из этой предыдущей темы
                $previousTheme = Theme::where('course_id', '=', $course->id)
                    ->where('index_number', '<', $theme->index_number)
                    ->orderBy('index_number', 'desc')
                    ->first();
                $previousLesson = Lesson::where('theme_id', '=', $previousTheme->id)
                    ->orderBy('index_number', 'desc')
                    ->first();
            }

            if ($previousLesson != null) {
                // Проверяем пройден ли предыдущий урок
                /** @var StudentLesson $studentLesson */
                $studentLesson = $previousLesson->student_lessons()->where('student_id', '=', $user->id)->first();
                if ($studentLesson != null && $studentLesson->is_finished == true) {
                    $this->syncUserLessons($lesson->id);
                    return $view;
                }
            }

            return redirect('/' . $lang . '/course-catalog/course/' . $course->id)
                ->with('error', __('default.pages.lessons.access_denied_message'));
        }

        // Если все уроки доступны сразу
        $courseLessons = $course->lessons()
            ->whereNotIn('type', [3, 4])
            ->pluck('id')
            ->toArray();
        $finishedLessons = $user->student_lesson()
            ->where('course_id', '=', $course->id)
            ->where('is_finished', '=', true)
            ->pluck('lesson_id')
            ->toArray();

        switch ($lesson->type) {
            case (3):
                // Вариант - Курсовая работа
                // Проверить завершенность уроков
                if (array_diff($courseLessons, $finishedLessons) != []) {
                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id)
                        ->with('error', __('default.pages.lessons.access_denied_message'));
                }
                break;
            case (4):
                // Вариант - Финальное тестирование
                // Проверить завершенность уроков
                if (array_diff($courseLessons, $finishedLessons) != []) {
                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id)
                        ->with('error', __('default.pages.lessons.access_denied_message'));
                }
                /** @var Lesson $courseWork */
                $courseWork = $course->lessons()
                    ->where('type', '=', 3)
                    ->first();
                if ($courseWork != null) {
                    /** @var StudentLesson $studentLesson */
                    $studentLesson = $courseWork->student_lessons()
                        ->where('student_id', '=', $user->id)
                        ->first();
                    if ($studentLesson == null || $studentLesson->is_finished == false) {
                        // Если есть курсовая, но она не завершена, вернуть обратно
                        return redirect('/' . $lang . '/course-catalog/course/' . $course->id)
                            ->with('error', __('default.pages.lessons.access_denied_message'));
                    }
                }
                break;
            default:
                break;
        }

        $this->syncUserLessons($lesson->id);
        return $view;
    }

    public function lessonFinished($lang, Request $request, Course $course, Lesson $lesson)
    {
        switch ($request->input('action')) {
            // Переход к следующему уроку
            case 'next_lesson':
                $user = Auth::user();
                $lesson_student = StudentLesson::whereLessonId($lesson->id)
                    ->whereStudentId($user->id)
                    ->first();

                // Пометить урок как пройденный
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
        $user = Auth::user();
        $studentLesson = StudentLesson::whereLessonId($lesson->id)
            ->whereStudentId($user->id)
            ->first();
        if ($studentLesson != null && $studentLesson->is_access == true) {
            return view("app.pages.student.lesson.homework_view", [
                "item" => $course,
                "lesson" => $lesson,
                "theme" => $theme
            ]);
        }

        return redirect('/' . $lang . '/course-catalog/course/' . $course->id)
            ->with('error', __('default.pages.lessons.access_denied_message'));
    }

    public function testView($lang, Course $course, Lesson $lesson)
    {
        $user = Auth::user();
        $studentLesson = StudentLesson::whereLessonId($lesson->id)
            ->whereStudentId($user->id)
            ->first();
        if ($studentLesson != null && $studentLesson->is_access == true && $studentLesson->is_finished != true) {
            return view("app.pages.student.lesson.test_view_lesson", [
                "item" => $course,
                "lesson" => $lesson
            ]);
        }

        return redirect('/' . $lang . '/course-catalog/course/' . $course->id)
            ->with('error', __('default.pages.lessons.access_denied_message'));
    }

    public function answerSend($lang, Request $request, Course $course, Lesson $lesson)
    {
        $user = Auth::user();
        $studentLesson = StudentLesson::whereLessonId($lesson->id)
            ->whereStudentId($user->id)
            ->first();

        if ($studentLesson != null && $studentLesson->is_access == true) {
            if ($lesson->type == 3) {
                $request->validate([
                    'answer' => 'required',
                    'another_files' => 'required|not_in:[]',
                ]);
            }

            $result = StudentLessonAnswer::whereLessonId($lesson->id)
                ->whereStudentId($user->id)
                ->first();

            if ($result == null) {
                $answer = new StudentLessonAnswer;
            } else {
                $answer = $result;
            }

            $answer->student_lesson_id = $studentLesson->id;
            $answer->student_id = $user->id;
            $answer->lesson_id = $lesson->id;

            switch ($request->input('action')) {
                case('homework'):
                case('coursework'):
                    $answer->type = 1;
                    $answer->answer = $request->get('answer', null);
                    // Видео с устройства
                    $video = $request->get('videos', null);
                    if ($video != $answer->videos) {
                        File::delete(public_path($answer->videos));
                        $answer->videos = $video;
                    }

                    // Аудио с устройства
                    $audio = $request->get('audios', null);
                    if ($audio != $answer->audios) {
                        File::delete(public_path($answer->audios));
                        $answer->audios = $audio;
                    }

                    // Другие материалы
                    $anotherFiles = $request->get('another_files', null);
                    if ($anotherFiles != $answer->another_files) {
                        File::delete(public_path($answer->audios));
                        $answer->another_files = $anotherFiles;
                    }

                    // Пометить урок как законченный
                    $studentLesson->is_finished = true;
                    $studentLesson->save();
                    break;
                case('test'):
                case('final_test'):
                    $answer->type = 0;
                    $answer->answer = json_encode($request->get('answers', []));
                    break;
            }

            $answer->save();

            switch ($lesson->type) {
                case 3:
                    $this->finishedCourse($course);
                    // Подтвердить квалификацию
                    $student_course = StudentCourse::whereStudentId($user->id)
                        ->whereCourseId($course->id)
                        ->first();
                    $student_course->is_qualificated = true;
                    $student_course->save();

                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('status', __('default.pages.lessons.coursework_send_success'));
                    break;
                case 4:
                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/test-result');
                    break;
                case 2:
                    if ($lesson->end_lesson_type == 0) {
                        return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/test-result');
                    }
                    break;
                default:
                    break;
            }

            return $this->nextLessonShow($lang, $course, $lesson);
        }
        return redirect('/' . $lang . '/course-catalog/course/' . $course->id)
            ->with('error', __('default.pages.lessons.access_denied_message'));
    }

    public function testResultView($lang, Request $request, Course $course, Lesson $lesson)
    {
        $user = Auth::user();
        $studentLesson = StudentLesson::whereLessonId($lesson->id)
            ->whereStudentId($user->id)
            ->first();
        if ($studentLesson == null || $studentLesson->is_access == false) {
            return redirect('/' . $lang . '/course-catalog/course/' . $course->id)
                ->with('error', __('default.pages.lessons.access_denied_message'));
        }

        $result = StudentLessonAnswer::whereLessonId($lesson->id)
            ->whereStudentId($user->id)
            ->first();
        if ($result) {
            $lessonQuestions = json_decode($lesson->practice)->questions;
            $userAnswers = json_decode($result->answer);

            $rightAnswers = [];
            foreach ($lessonQuestions as $key => $question) {
                $rightAnswers[] = $question->answers[0];
            }

            $testResults = array_diff($userAnswers, $rightAnswers);

            $rightAnswers = 0;
            foreach ($lessonQuestions as $key => $question) {
                if (!array_key_exists($key, $testResults)) {
                    $rightAnswers++;
                }
            }

            // Если кол-во правильных ответов достаточно
            if ($rightAnswers >= json_decode($lesson->practice)->passingScore) {
                // Пометить урок как законченный
                $studentLesson->is_finished = true;
                $studentLesson->save();

                $this->finishedCourse($course);
            }


            return view("app.pages.student.lesson.test_results_view", [
                "item" => $course,
                "lesson" => $lesson,
                "results" => $testResults,
                "right_answers" => $rightAnswers
            ]);
        }

        return abort(404);
    }


    private function nextLessonShow($lang, Course $course, Lesson $lesson)
    {
        $user = Auth::user();
        $studentCourse = StudentCourse::whereStudentId($user->id)
            ->whereCourseId($course->id)
            ->first();

        if ($studentCourse != null && $studentCourse->is_finished == false) {
            // Получить следующий урок
            $currentTheme = $lesson->themes;
            $nextLesson = Lesson::where('index_number', '>', $lesson->index_number)
                ->where('theme_id', '=', $currentTheme->id)
                ->orderBy('index_number', 'asc')
                ->first();

            if ($nextLesson == null) {
                $nextTheme = Theme::where('course_id', '=', $course->id)
                    ->where('index_number', '>', $currentTheme->index_number)
                    ->orderBy('index_number', 'asc')
                    ->first();
                if ($nextTheme != null) {
                    $nextLesson = Lesson::where('theme_id', '=', $nextTheme->id)
                        ->orderBy('index_number', 'asc')
                        ->first();
                }
            }

            // Переход к следующему уроку
            if ($nextLesson != null) {
                // Установка доступа к следующему уроку
                $this->syncUserLessons($nextLesson->id);
                // Проверить окончание курса
                $this->finishedCourse($course);
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $nextLesson->id);
            }

            /** @var Lesson $courseWork */
            $courseWork = $course->lessons()
                ->where('type', '=', 3)
                ->first();
            if ($courseWork != null && $courseWork->id != $lesson->id) {
                // Установка доступа к следующему уроку
                $this->syncUserLessons($courseWork->id);
                // Проверить окончание курса
                $this->finishedCourse($course);
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $courseWork->id);
            }

            /** @var Lesson $finalTest */
            $finalTest = $course->lessons()
                ->where('type', '=', 4)
                ->first();
            if ($finalTest != null && $finalTest->id != $lesson->id) {
                // Установка доступа к следующему уроку
                $this->syncUserLessons($finalTest->id);
                // Проверить окончание курса
                $this->finishedCourse($course);
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $finalTest->id);
            }
        }

        return redirect('/' . $lang . '/course-catalog/course/' . $course->id);
    }

    private function syncUserLessons(int $lesson_id)
    {
        $user = Auth::user();
        $item = StudentLesson::where('lesson_id', '=', $lesson_id)
            ->where('student_id', '=', $user->id)
            ->first();

        if ($item == null) {
            $item = new StudentLesson;
            $item->lesson_id = $lesson_id;
            $item->student_id = $user->id;
            $item->is_access = true;
            $item->save();
        }
    }

    private function finishedCourse(Course $course)
    {
        $user = Auth::user();

        // Получить все уроки данного курса
        $lessonIds = $course->lessons->pluck('id')->toArray();

        // Получить все завершенные уроки данного курса
        $studentFinishedLessonsCount = Lesson::whereHas('student_lessons', function ($q) use ($user) {
            $q->where('student_lesson.student_id', '=', $user->id);
            $q->where('student_lesson.is_finished', '=', true);
        })
            ->whereIn('id', $lessonIds)
            ->count();

        // Если кол-во уроков и кол-во завершенных уроков равно, то отметить курс как завершенный
        if ($course->lessons->count() == $studentFinishedLessonsCount) {
            $studentCourse = StudentCourse::whereStudentId($user->id)
                ->where('course_id', '=', $course->id)
                ->first();
            if ($studentCourse->is_finished == false) {
                $studentCourse->is_finished = true;
                $studentCourse->save();

                // Проверить наличие сертификата и если нет, то создать
                $userCertificate = StudentCertificate::whereUserId($user->id)
                    ->whereCourseId($course->id)
                    ->first();
                if ($userCertificate == null) {
                    $this->saveCertificates($course);
                }

                // Присвоить обучающемуся полученные навыки
                $user->skills()->sync($course->skills->pluck('id')->toArray(), false);

                // Отправить уведомление
                $notification_name = "notifications.course_student_finished";
                NotificationsHelper::createNotification($notification_name, $course->id, Auth::user()->id);
            }
        }
    }

    private function saveCertificates(Course $course)
    {
        $user = Auth::user();
        $languages = ["ru", "kk"];

        $certificate = new StudentCertificate;
        $certificate->user_id = $user->id;
        $certificate->course_id = $course->id;
        $certificate->save();

        $data = [
            'author_name' => $course->user->company_name,
            'student_name' => $user->student_info->name,
            'duration' => $course->lessons->sum('duration'),
            'course_name' => $course->name,
            'skills' => $course->skills,
            'certificate_id' => sprintf("%012d", $certificate->id) . '-' . date('dmY')
        ];

        foreach ($languages as $language) {
            try {
                $template = 'app.pages.page.pdf.certificate_' . $course->certificate_id . '_' . $language;
                $pdf = PDF::loadView($template, ['data' => $data]);
                $pdf = $pdf->setPaper('a4', 'portrait');

                $path = public_path('users/user_' . $user->id . '');
                $pdfPath = $path . '/' . 'course_' . $course->id . '_certificate_' . $language . '.pdf';
                $pdf->save($pdfPath);

                $pdfToImage = new \Spatie\PdfToImage\Pdf($pdfPath);
                $pngPath = $path . '/' . 'course_' . $course->id . '_image_' . $language . '.png';
                $pdfToImage->saveImage($pngPath);
            } catch (\InvalidArgumentException $e) {
                $e->getMessage();
            }
        }

        $filePath = '/users/user_' . $user->id;

        $certificate->pdf_ru = $filePath . '/' . 'course_' . $course->id . '_certificate_ru.pdf';
        $certificate->pdf_kk = $filePath . '/' . 'course_' . $course->id . '_certificate_kk.pdf';
        $certificate->png_ru = $filePath . '/' . 'course_' . $course->id . '_image_ru.png';
        $certificate->png_kk = $filePath . '/' . 'course_' . $course->id . '_image_kk.png';

        $certificate->save();

        try {
            $cert = base64_encode(file_get_contents(env('APP_URL') . $certificate->png_ru));
            $this->putNewSkills($user->student_info->uid, $course, $cert);
        } catch (\ErrorException $e) {
            $e->getMessage();
        }
    }

    private function putNewSkills($uid, Course $course, $cert)
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
            $response = $client->request('PUT', config('enbek.base_url') . '/ru/api/put-navyk-from-obuch', [
                'body' => json_encode($body),
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);
        } catch (BadResponseException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

        return [
            'success' => true,
            'message' => null,
        ];
    }
}
