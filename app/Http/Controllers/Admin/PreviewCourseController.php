<?php

namespace App\Http\Controllers\Admin;

use App\Extensions\FormatDate;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonAttachments;
use App\Models\Theme;
use App\Models\User;

use Illuminate\Http\Request;

class PreviewCourseController extends Controller
{
    public function viewCourse($lang, Course $item)
    {
        $course_author = User::whereId($item->author_id)->first();

        $courses = $course_author->courses()->get();
        // Все оценки всех курсов
        $rates = [];
        foreach ($courses as $course) {
            foreach ($course->rate as $rate) {
                array_push($rates, $rate->rate);
            }
        }
        // Все ученики автора
        $author_students = [];
        foreach ($courses->unique('student_id') as $course) {
            foreach ($course->course_members as $member) {
                array_push($author_students, $member);
            }
        }
        // Все ученики закончившие курс
        $author_students_finished = [];
        foreach ($courses as $course) {
            foreach ($course->course_members->where('is_finished', '=', true) as $member) {
                array_push($author_students_finished, $member);
            }
        }
        // Оценка автора исходя из всех оценок
        if (count($rates) == 0) {
            $average_rates = 0;
        } else {
            $average_rates = array_sum($rates) / count($rates);
        }

        $themes = $item->themes()->orderBy('index_number', 'asc')->get();
        $lessons_count = count(Lesson::where('course_id', '=', $item->id)->get());

        $lessons = $item->lessons;
        $videos_count = [];
        $audios_count = [];
        $attachments_count = [];

        foreach ($lessons as $lesson) {
            if ($lesson->lesson_attachment != null) {
                if ($lesson->lesson_attachment->videos != null) {
                    $videos_count[] = count(json_decode($lesson->lesson_attachment->videos));
                }
                if ($lesson->lesson_attachment->audios != null) {
                    $audios_count[] = count(json_decode($lesson->lesson_attachment->audios));
                }
                if ($lesson->lesson_attachment->another_files != null) {
                    $attachments_count[] = count(json_decode($lesson->lesson_attachment->another_files));
                }
            }

        }

        $untheme_lessons = Lesson::whereCourseId($item->id)
            ->whereThemeId(null)
            ->whereNotIn('type', [3, 4])
            ->orderBy('index_number', 'asc')
            ->get();

        foreach ($themes as $theme) {
            $theme->item_type = 'theme';
        }

        foreach ($untheme_lessons as $unthemes_lesson) {
            $unthemes_lesson->item_type = 'lesson';
        }

        $course_data_items = $themes->merge($untheme_lessons)->sortBy('index_number')->values();

        return view('admin.v2.pages.courses.course_frame', [
            "item" => $item,
            "themes" => $themes,
            "lessons_count" => $lessons_count,
            "rates" => $rates,
            "author_students" => $author_students,
            "courses" => $courses,
            "author_students_finished" => $author_students_finished,
            "average_rates" => $average_rates,
            "videos_count" => array_sum($videos_count),
            "audios_count" => array_sum($audios_count),
            "attachments_count" => array_sum($attachments_count),
            'course_data_items' => $course_data_items
        ]);

    }

    public function viewLesson($lang, Course $item, Lesson $lesson)
    {
        $time = FormatDate::convertMunitesToTime($lesson->duration);

        // Получить все файлы урока
        $lesson_attachments = LessonAttachments::whereId($lesson->id)->first();

        $nextLesson = $this->getNextLesson($lang, $item, $lesson);
//        dd($nextLesson);
        return view("admin.v2.pages.courses.lesson_preview.view_lesson", [
            "item" => $item,
            "lesson" => $lesson,
            "time" => $time,
            "lesson_attachments" => $lesson_attachments,
            "nextLesson" => $nextLesson,
        ]);

    }

    private function getNextLesson($lang, Course $course, Lesson $lesson)
    {
        $currentTheme = $lesson->themes;
        if ($currentTheme != null) {
            $nextLesson = Lesson::where('index_number', '>', $lesson->index_number)
                ->where('theme_id', '=', $currentTheme->id)
                ->orderBy('index_number', 'asc')
                ->first();

            if ($nextLesson == null) {
                $nextTheme = Theme::where('course_id', '=', $course->id)
                    ->where('index_number', '>', $currentTheme->index_number)
                    ->orderBy('index_number', 'asc')
                    ->first();
                $nextUnthemeLesson = Lesson::where('index_number', '>', $lesson->index_number)
                    ->whereCourseId($course->id)
                    ->whereThemeId(null)
                    ->whereNotIn('type', [3])
                    ->orderBy('index_number', 'asc')
                    ->first();
                if ($nextTheme != null && $nextUnthemeLesson != null) {
                    if ($nextTheme->index_number < $nextUnthemeLesson->index_number) {
                        $nextLesson = Lesson::where('theme_id', '=', $nextTheme->id)
                            ->orderBy('index_number', 'desc')
                            ->first();
                    } else {
                        $nextLesson = $nextUnthemeLesson;
                    }
                } else if ($nextTheme != null && $nextUnthemeLesson == null) {
                    $nextLesson = Lesson::where('theme_id', '=', $nextTheme->id)
                        ->orderBy('index_number', 'asc')
                        ->first();
                } else {
                    $nextLesson = $nextUnthemeLesson;
                }
            }
        } else {
            $nextTheme = Theme::where('course_id', '=', $course->id)
                ->where('index_number', '>', $lesson->index_number)
                ->orderBy('index_number', 'asc')
                ->first();
            $nextUnthemeLesson = Lesson::where('index_number', '>', $lesson->index_number)
                ->whereCourseId($course->id)
                ->whereThemeId(null)
                ->whereNotIn('type', [3])
                ->orderBy('index_number', 'asc')
                ->first();
            if ($nextTheme != null && $nextUnthemeLesson != null) {
                if ($nextTheme->index_number < $nextUnthemeLesson->index_number) {
                    $nextLesson = Lesson::where('theme_id', '=', $nextTheme->id)
                        ->orderBy('index_number', 'desc')
                        ->first();
                } else {
                    $nextLesson = $nextUnthemeLesson;
                }
            } else if ($nextTheme != null && $nextUnthemeLesson == null) {
                $nextLesson = Lesson::where('theme_id', '=', $nextTheme->id)
                    ->orderBy('index_number', 'desc')
                    ->first();
            } else {
                $nextLesson = $nextUnthemeLesson;
            }
        }
        return $nextLesson;
    }

    public function homeWorkView($lang, Request $request, Course $course, Lesson $lesson)
    {
        return view("admin.v2.pages.courses.lesson_preview.homework_view_lesson", [
            "item" => $course,
            "lesson" => $lesson
        ]);

    }

    public function lessonFinished($lang, Request $request, Course $course, Lesson $lesson)
    {
        switch ($request->input('action')) {
            // Переход к домашнему заданию
            case 'homework':
                return redirect('/' . $lang . '/admin/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/admin-homework');
            // Переход к тесту
            case 'test':
            case 'final-test':
                return redirect('/' . $lang . '/admin/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/admin-test');
                break;
            case 'coursework':
                return redirect('/' . $lang . '/admin/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/admin-coursework');
                break;
        }

        return redirect()->back();
    }

    public function submitHomeWork($lang, Request $request, Course $course, Lesson $lesson)
    {
        return redirect("/" . $lang . "/admin/moderator-course-iframe-" . $course->id);
    }

    public function testView($lang, Request $request, Course $course, Lesson $lesson)
    {
        return view("admin.v2.pages.courses.lesson_preview.test_view_lesson", [
            "item" => $course,
            "lesson" => $lesson
        ]);
    }

    public function submitTest($lang, Request $request, Course $course, Lesson $lesson)
    {
        $right_answers = [];

        foreach (json_decode($lesson->practice)->questions as $key => $question) {
            $right_answers[] = $question->answers[0];
        }

        $answers = $request->answers;

        $test_results = array_diff($answers, $right_answers);

        $right_answers = 0;

        foreach (json_decode($lesson->practice)->questions as $key => $question) {
            if (!array_key_exists($key, $test_results)) {
                $right_answers++;
            }
        }

        return view("admin.v2.pages.courses.lesson_preview.test_results_view", [
            "item" => $course,
            "lesson" => $lesson,
            "results" => $test_results,
            "right_answers" => $right_answers
        ]);
    }

}
