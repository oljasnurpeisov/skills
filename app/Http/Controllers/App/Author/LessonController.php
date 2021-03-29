<?php

namespace App\Http\Controllers\App\Author;

use App\Extensions\FormatDate;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonAttachments;
use App\Models\LessonsType;
use App\Models\Skill;
use App\Models\Theme;
use App\Models\Type_of_ownership;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Orchestra\Parser\Xml\Facade as XmlParser;
use function PHPUnit\Framework\isEmpty;


class LessonController extends Controller
{
    public function createLesson($lang, Course $item, Theme $theme)
    {
        $lessons_type = LessonsType::all();
        if ($item->author_id == Auth::user()->id) {
            return view("app.pages.author.courses.create_lesson", [
                "item" => $item,
                "theme" => $theme,
                "lessons_type" => $lessons_type
            ]);
        } else {
            return redirect("/" . $lang . "/my-courses");
        }
    }

    public function createUnthemeLesson($lang, Course $item)
    {
        $lessons_type = LessonsType::all();
        if ($item->author_id == Auth::user()->id) {
            return view("app.pages.author.courses.create_lesson", [
                "item" => $item,
                "theme" => null,
                "lessons_type" => $lessons_type
            ]);
        } else {
            return redirect("/" . $lang . "/my-courses");
        }
    }

    public function storeLesson($lang, Request $request, Course $course, Theme $theme)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'duration' => 'required|numeric|gt:0'
        ]);

        $last_id = Lesson::whereHas('themes', function ($q) use ($theme) {
            $q->where('themes.id', '=', $theme->id);
        })->orderBy('index_number', 'desc')->latest()->first();

        if ($last_id) {
            $index = $last_id->index_number + 1;
        } else {
            $index = 0;
        }

        $item = new Lesson;
        $item->theme_id = $theme->id;
        $item->course_id = $course->id;
        $item->name = $request->name;
        $item->index_number = $index;

        if ($request->type == 'theory') {
            $item->type = 1;

            $item->end_lesson_type = 2;

        } else {
            $item->type = 2;

            if ($request->practiceType == 'test') {
                $item->end_lesson_type = 0;

                $data = array(
                    "questions" => array(),
                    "passingScore" => $request->passingScore,
                    "mixAnswers" => $request->mixAnswers
                );

                foreach ($request->questions as $key => $question) {
                    $answers = $request->answers[$key];

                    if (isset($request->isPictures[$key])) {
                        foreach ($answers as $k => $answer) {
                            if (is_array(json_decode($answer))) {
                                $answers[$k] = json_decode($answer)[0];
                            }
                        }
                    }

                    $data['questions'][] = array(
                        'name' => $request->questions[$key],
                        'is_pictures' => isset($request->isPictures[$key]),
                        'answers' => $answers
                    );
                }

                $item->practice = json_encode($data);


            } else if ($request->practiceType == 'homework') {
                $item->end_lesson_type = 1;

                $item->practice = $request->homework;
            }
        }

        $item->duration = $request->duration;
        $item->theory = $request->theory;

        if (($request->image != $item->image)) {
            File::delete(public_path($item->image));

            $item->image = $request->image;
        }

        $item->save();

        // Вложения к уроку
        $item_attachments = new LessonAttachments;
        $item_attachments->lesson_id = $item->id;

        // Ссылки на видео курса
        $item_attachments->videos_link = json_encode($request->videos_link);
        // Ссылки на видео курса для слабовидящих
        if ($request->videos_poor_vision_link) {
            $item_attachments->videos_poor_vision_link = json_encode($request->videos_poor_vision_link);
        }
        // Ссылки на видео курса для лиц с нарушениями слуха
        if ($request->videos_poor_hearing_link) {
            $item_attachments->videos_poor_hearing_link = json_encode($request->videos_poor_hearing_link);
        }
        // Видео с устройства
        if (($request->videos != $item_attachments->videos)) {
            File::delete(public_path($item_attachments->videos));

            $item_attachments->videos = $request->videos;
        }
        // Видео с устройства для слабовидящих
        if (($request->videos_poor_vision != $item_attachments->videos_poor_vision)) {
            File::delete(public_path($item_attachments->videos_poor_vision));

            $item_attachments->videos_poor_vision = $request->videos_poor_vision;
        }
        // Видео с устройства для лиц с нарушениями слуха
        if (($request->videos_poor_hearing != $item_attachments->videos_poor_hearing)) {
            File::delete(public_path($item_attachments->videos_poor_hearing));

            $item_attachments->videos_poor_hearing = $request->videos_poor_hearing;
        }
        // Аудио с устройства
        if (($request->audios != $item_attachments->audios)) {
            File::delete(public_path($item_attachments->audios));

            $item_attachments->audios = $request->audios;
        }
        // Аудио с устройства для слабовидящих
        if (($request->audios_poor_vision != $item_attachments->audios_poor_vision)) {
            File::delete(public_path($item_attachments->audios_poor_vision));

            $item_attachments->audios_poor_vision = $request->audios_poor_vision;
        }
        // Аудио с устройства для лиц с нарушениями слуха
        if (($request->audios_poor_hearing != $item_attachments->audios_poor_hearing)) {
            File::delete(public_path($item_attachments->audios_poor_hearing));

            $item_attachments->audios_poor_hearing = $request->audios_poor_hearing;
        }
        // Другие материалы
        if (($request->another_files != $item_attachments->another_files)) {
            File::delete(public_path($item_attachments->another_files));

            $item_attachments->another_files = $request->another_files;
        }
        // Другие материалы для слабовидящих
        if (($request->another_files_poor_vision != $item_attachments->another_files_poor_vision)) {
            File::delete(public_path($item_attachments->another_files_poor_vision));

            $item_attachments->another_files_poor_vision = $request->another_files_poor_vision;
        }
        // Другие материалы для лиц с нарушениями слуха
        if (($request->another_files_poor_hearing != $item_attachments->another_files_poor_hearing)) {
            File::delete(public_path($item_attachments->another_files_poor_hearing));

            $item_attachments->another_files_poor_hearing = $request->another_files_poor_hearing;
        }

        $item_attachments->save();

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $course->id)->with('status', __('default.pages.lessons.create_request_message'));
    }

    public function storeUnthemeLesson($lang, Request $request, Course $course)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'duration' => 'required|numeric|gt:0'
        ]);

        // Получить послений index из тем и уроков без тем
        $last_theme_index_id = Theme::whereCourseId($course->id)->get();
        $last_lesson_index_id = Lesson::whereCourseId($course->id)
            ->whereThemeId(null)
            ->whereNotIn('type', [3, 4])
            ->get();
        $last_index = $last_theme_index_id
            ->merge($last_lesson_index_id)
            ->pluck('index_number')
            ->toArray();
        sort($last_index);
        $last_index = end($last_index);

        if ($last_index) {
            $index = $last_index + 1;
        } else {
            $index = 0;
        }

        $item = new Lesson;
        $item->course_id = $course->id;
        $item->name = $request->name;
        $item->index_number = $index;

        if ($request->type == 'theory') {
            $item->type = 1;

            $item->end_lesson_type = 2;

        } else {
            $item->type = 2;

            if ($request->practiceType == 'test') {
                $item->end_lesson_type = 0;

                $data = array(
                    "questions" => array(),
                    "passingScore" => $request->passingScore,
                    "mixAnswers" => $request->mixAnswers
                );

                foreach ($request->questions as $key => $question) {
                    $answers = $request->answers[$key];

                    if (isset($request->isPictures[$key])) {
                        foreach ($answers as $k => $answer) {
                            if (is_array(json_decode($answer))) {
                                $answers[$k] = json_decode($answer)[0];
                            }
                        }
                    }

                    $data['questions'][] = array(
                        'name' => $request->questions[$key],
                        'is_pictures' => isset($request->isPictures[$key]),
                        'answers' => $answers
                    );
                }

                $item->practice = json_encode($data);


            } else if ($request->practiceType == 'homework') {
                $item->end_lesson_type = 1;

                $item->practice = $request->homework;
            }
        }

        $item->duration = $request->duration;
        $item->theory = $request->theory;

        if (($request->image != $item->image)) {
            File::delete(public_path($item->image));

            $item->image = $request->image;
        }

        $item->save();

        // Вложения к уроку
        $item_attachments = new LessonAttachments;
        $item_attachments->lesson_id = $item->id;

        // Ссылки на видео курса
        $item_attachments->videos_link = json_encode($request->videos_link);
        // Ссылки на видео курса для слабовидящих
        if ($request->videos_poor_vision_link) {
            $item_attachments->videos_poor_vision_link = json_encode($request->videos_poor_vision_link);
        }
        // Ссылки на видео курса для лиц с нарушениями слуха
        if ($request->videos_poor_hearing_link) {
            $item_attachments->videos_poor_hearing_link = json_encode($request->videos_poor_hearing_link);
        }
        // Видео с устройства
        if (($request->videos != $item_attachments->videos)) {
            File::delete(public_path($item_attachments->videos));

            $item_attachments->videos = $request->videos;
        }
        // Видео с устройства для слабовидящих
        if (($request->videos_poor_vision != $item_attachments->videos_poor_vision)) {
            File::delete(public_path($item_attachments->videos_poor_vision));

            $item_attachments->videos_poor_vision = $request->videos_poor_vision;
        }
        // Видео с устройства для лиц с нарушениями слуха
        if (($request->videos_poor_hearing != $item_attachments->videos_poor_hearing)) {
            File::delete(public_path($item_attachments->videos_poor_hearing));

            $item_attachments->videos_poor_hearing = $request->videos_poor_hearing;
        }
        // Аудио с устройства
        if (($request->audios != $item_attachments->audios)) {
            File::delete(public_path($item_attachments->audios));

            $item_attachments->audios = $request->audios;
        }
        // Аудио с устройства для слабовидящих
        if (($request->audios_poor_vision != $item_attachments->audios_poor_vision)) {
            File::delete(public_path($item_attachments->audios_poor_vision));

            $item_attachments->audios_poor_vision = $request->audios_poor_vision;
        }
        // Аудио с устройства для лиц с нарушениями слуха
        if (($request->audios_poor_hearing != $item_attachments->audios_poor_hearing)) {
            File::delete(public_path($item_attachments->audios_poor_hearing));

            $item_attachments->audios_poor_hearing = $request->audios_poor_hearing;
        }
        // Другие материалы
        if (($request->another_files != $item_attachments->another_files)) {
            File::delete(public_path($item_attachments->another_files));

            $item_attachments->another_files = $request->another_files;
        }
        // Другие материалы для слабовидящих
        if (($request->another_files_poor_vision != $item_attachments->another_files_poor_vision)) {
            File::delete(public_path($item_attachments->another_files_poor_vision));

            $item_attachments->another_files_poor_vision = $request->another_files_poor_vision;
        }
        // Другие материалы для лиц с нарушениями слуха
        if (($request->another_files_poor_hearing != $item_attachments->another_files_poor_hearing)) {
            File::delete(public_path($item_attachments->another_files_poor_hearing));

            $item_attachments->another_files_poor_hearing = $request->another_files_poor_hearing;
        }

        $item_attachments->save();

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $course->id)->with('status', __('default.pages.lessons.create_request_message'));
    }

    public function editLesson($lang, Course $course, Lesson $lesson)
    {
        $theme = Theme::where('id', '=', $lesson->theme_id)->first();

        if ($theme) {
            $course_theme = $theme->courses()->first();
            $lesson_theme = $theme->with(["lessons" => function ($q) use ($lesson) {
                $q->where('lessons.id', '=', $lesson->id);
            }])->where('id', '=', $theme->id)->first();
        }

        $lessons_type = LessonsType::all();

        if ($course->author_id == Auth::user()->id) {
            return view("app.pages.author.courses.edit_lesson", [
                "theme" => $theme,
                "item" => $lesson,
                "lessons_type" => $lessons_type,
                "course" => $course
            ]);
        } else {
            return redirect("/" . app()->getLocale() . "/my-courses");
        }
    }

    public function updateLesson($lang, Request $request, Course $course, Lesson $item)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'duration' => 'required|numeric|gt:0'
        ]);

        $item->name = $request->name;

        if ($request->type == 'theory') {
            $item->type = 1;

            $item->end_lesson_type = 2;
        } else {
            $item->type = 2;

            if ($request->practiceType == 'test') {
                $item->end_lesson_type = 0;

                $data = array(
                    "questions" => array(),
                    "passingScore" => $request->passingScore,
                    "mixAnswers" => $request->mixAnswers
                );

                foreach ($request->questions as $key => $question) {
                    $answers = $request->answers[$key];

                    if (isset($request->isPictures[$key])) {
                        foreach ($answers as $k => $answer) {
                            if (is_array(json_decode($answer))) {
                                $answers[$k] = json_decode($answer)[0];
                            }
                        }
                    }

                    $data['questions'][] = array(
                        'name' => $request->questions[$key],
                        'is_pictures' => isset($request->isPictures[$key]),
                        'answers' => $answers
                    );
                }

                $item->practice = json_encode($data);


            } else if ($request->practiceType == 'homework') {
                $item->end_lesson_type = 1;

                $item->practice = $request->homework;
            }
        }

        $item->duration = $request->duration;
        $item->theory = $request->theory;

        if (($request->image != $item->image)) {
            File::delete(public_path($item->image));

            $item->image = $request->image;
        }

        $item->save();

        // Вложения к уроку
        $item_attachments = LessonAttachments::where('lesson_id', '=', $item->id)->first();

        // Ссылки на видео курса
        $item_attachments->videos_link = json_encode($request->videos_link);

        // Ссылки на видео курса для слабовидящих
        if ($request->videos_poor_vision_link) {
            $item_attachments->videos_poor_vision_link = json_encode($request->videos_poor_vision_link);
        }
        // Ссылки на видео курса для лиц с нарушениями слуха
        if ($request->videos_poor_hearing_link) {
            $item_attachments->videos_poor_hearing_link = json_encode($request->videos_poor_hearing_link);
        }

        // Видео с устройства
        $videos = array_merge(json_decode($request->localVideo) ?? [], $request->localVideoStored ?? []);

        if ($videos != $item_attachments->videos) {

            $item_attachments->videos = $videos;

            $item_attachments->save();
        }
        // Аудио с устройства
        $audios = array_merge(json_decode($request->localAudio) ?? [], $request->localAudioStored ?? []);

        if ($audios != $item_attachments->audios) {

            $item_attachments->audios = $audios;

            $item_attachments->save();
        }
        // Другие файлы с устройства
        $another_files = array_merge(json_decode($request->localDocuments) ?? [], $request->localDocumentsStored ?? []);

        if ($another_files != $item_attachments->another_files) {

            $item_attachments->another_files = $another_files;

            $item_attachments->save();
        }
        // Видео с устройства (для слабовидящих)
        $videos_poor_vision = array_merge(json_decode($request->localVideo1) ?? [], $request->localVideoStored1 ?? []);

        if ($videos_poor_vision != $item_attachments->videos_poor_vision) {

            $item_attachments->videos_poor_vision = $videos_poor_vision;

            $item_attachments->save();
        }
        // Аудио с устройства (для слабовидящих)
        $audios_poor_vision = array_merge(json_decode($request->localAudio1) ?? [], $request->localAudioStored1 ?? []);

        if ($audios_poor_vision != $item_attachments->audios_poor_vision) {

            $item_attachments->audios_poor_vision = $audios_poor_vision;

            $item_attachments->save();
        }
        // Другие файлы с устройства (для слабовидящих)
        $another_files_poor_vision = array_merge(json_decode($request->localDocuments1) ?? [], $request->localDocumentsStored1 ?? []);

        if ($another_files_poor_vision != $item_attachments->another_files_poor_vision) {

            $item_attachments->another_files_poor_vision = $another_files_poor_vision;

            $item_attachments->save();
        }

        // Видео с устройства (для слабовидящих)
        $videos_poor_hearing = array_merge(json_decode($request->localVideo2) ?? [], $request->localVideoStored2 ?? []);

        if ($videos_poor_hearing != $item_attachments->videos_poor_hearing) {

            $item_attachments->videos_poor_hearing = $videos_poor_hearing;

            $item_attachments->save();
        }
        // Аудио с устройства (для слабовидящих)
        $audios_poor_hearing = array_merge(json_decode($request->localAudio2) ?? [], $request->localAudioStored2 ?? []);

        if ($audios_poor_hearing != $item_attachments->audios_poor_hearing) {

            $item_attachments->audios_poor_hearing = $audios_poor_hearing;

            $item_attachments->save();
        }
        // Другие файлы с устройства (для слабовидящих)
        $another_files_poor_hearing = array_merge(json_decode($request->localDocuments2) ?? [], $request->localDocumentsStored2 ?? []);

        if ($another_files_poor_hearing != $item_attachments->another_files_poor_hearing) {

            $item_attachments->another_files_poor_hearing = $another_files_poor_hearing;

            $item_attachments->save();
        }

        $item_attachments->save();

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $course->id);
    }

    public function createCoursework($lang, Course $item)
    {
        // Проверка владельца курса
        if ($item->author_id == Auth::user()->id) {
            // Проверка существования курсовой работы
            $coursework = Lesson::where('course_id', '=', $item->id)->where('type', '=', 3)->first();
            if (empty($coursework)) {
                return view("app.pages.author.courses.create_coursework", [
                    "item" => $item
                ]);
                //Если курсовой работы нет
            } else {
                return redirect("/" . $lang . "/my-courses/course/" . $item->id)->with('error', __('default.pages.lessons.coursework_count_error_message'));
            }
            //Если автор не владелец курса
        } else {
            return redirect("/" . $lang . "/my-courses");
        }
    }

    public function storeCoursework($lang, Request $request, Course $course)
    {
        $this->validate($request, [
            'duration' => 'required|numeric|gt:0'
        ]);

        $item = new Lesson;
        $item->course_id = $course->id;
        $item->index_number = 1;
        $item->type = 3; // Тип "Курсовая работа"
        $item->end_lesson_type = 1; // Тип "Домашняя работа"
        $item->duration = $request->duration;
        $item->theory = $request->theory;
        $item->coursework_task = $request->coursework_task;

        if (($request->image != $item->image)) {
            File::delete(public_path($item->image));

            $item->image = $request->image;
        }

        $item->save();

        $item_attachments = new LessonAttachments;
        $item_attachments->lesson_id = $item->id;

        // Ссылки на видео курса
        $item_attachments->videos_link = json_encode($request->videos_link);

        // Ссылки на видео курса для слабовидящих
        if ($request->videos_poor_vision_link) {
            $item_attachments->videos_poor_vision_link = json_encode($request->videos_poor_vision_link);
        }

        // Видео с устройства
        if (($request->videos != $item_attachments->videos)) {
            File::delete(public_path($item_attachments->videos));

            $item_attachments->videos = $request->videos;
        }
        // Видео с устройства для слабовидящих
        if (($request->videos_poor_vision != $item_attachments->videos_poor_vision)) {
            File::delete(public_path($item_attachments->videos_poor_vision));

            $item_attachments->videos_poor_vision = $request->videos_poor_vision;
        }
        // Аудио с устройства
        if (($request->audios != $item_attachments->audios)) {
            File::delete(public_path($item_attachments->audios));

            $item_attachments->audios = $request->audios;
        }
        // Аудио с устройства для слабовидящих
        if (($request->audios_poor_vision != $item_attachments->audios_poor_vision)) {
            File::delete(public_path($item_attachments->audios_poor_vision));

            $item_attachments->audios_poor_vision = $request->audios_poor_vision;
        }
        // Другие материалы
        if (($request->another_files != $item_attachments->another_files)) {
            File::delete(public_path($item_attachments->another_files));

            $item_attachments->another_files = $request->another_files;
        }
        // Другие материалы для слабовидящих
        if (($request->another_files_poor_vision != $item_attachments->another_files_poor_vision)) {
            File::delete(public_path($item_attachments->another_files_poor_vision));

            $item_attachments->another_files_poor_vision = $request->another_files_poor_vision;
        }

        $item_attachments->save();

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $course->id)->with('status', __('default.pages.lessons.create_coursework_message'));
    }

    public function editCoursework($lang, Course $course)
    {
        if ($course->author_id == Auth::user()->id) {
            $item = Lesson::where('course_id', '=', $course->id)->where('type', '=', 3)->first();
            return view("app.pages.author.courses.edit_coursework", [
                "course" => $course,
                "item" => $item
            ]);
        } else {
            return redirect("/" . app()->getLocale() . "/my-courses");
        }

    }

    public function updateCoursework($lang, Request $request, Course $course)
    {
        $this->validate($request, [
            'duration' => 'required|numeric|gt:0'
        ]);

        $item = Lesson::where('course_id', '=', $course->id)->where('type', '=', 3)->first();
        $item->duration = $request->duration;
        $item->theory = $request->theory;

        $item->coursework_task = $request->coursework_task;

        if (($request->image != $item->image)) {
            File::delete(public_path($item->image));

            $item->image = $request->image;
        }

        $item->save();

        $item_attachments = LessonAttachments::where('lesson_id', '=', $item->id)->first();

        // Ссылки на видео курса
        $item_attachments->videos_link = json_encode($request->videos_link);

        // Ссылки на видео курса для слабовидящих
        if ($request->videos_poor_vision_link) {
            $item_attachments->videos_poor_vision_link = json_encode($request->videos_poor_vision_link);
        }

        // Видео с устройства
        $videos = array_merge(json_decode($request->localVideo) ?? [], $request->localVideoStored ?? []);

        if ($videos != $item_attachments->videos) {

            $item_attachments->videos = $videos;

            $item_attachments->save();
        }
        // Аудио с устройства
        $audios = array_merge(json_decode($request->localAudio) ?? [], $request->localAudioStored ?? []);

        if ($audios != $item_attachments->audios) {

            $item_attachments->audios = $audios;

            $item_attachments->save();
        }
        // Другие файлы с устройства
        $another_files = array_merge(json_decode($request->localDocuments) ?? [], $request->localDocumentsStored ?? []);

        if ($another_files != $item_attachments->another_files) {

            $item_attachments->another_files = $another_files;

            $item_attachments->save();
        }
        // Видео с устройства (для слабовидящих)
        $videos_poor_vision = array_merge(json_decode($request->localVideo1) ?? [], $request->localVideoStored1 ?? []);

        if ($videos_poor_vision != $item_attachments->videos_poor_vision) {

            $item_attachments->videos_poor_vision = $videos_poor_vision;

            $item_attachments->save();
        }
        // Аудио с устройства (для слабовидящих)
        $audios_poor_vision = array_merge(json_decode($request->localAudio1) ?? [], $request->localAudioStored1 ?? []);

        if ($audios_poor_vision != $item_attachments->audios_poor_vision) {

            $item_attachments->audios_poor_vision = $audios_poor_vision;

            $item_attachments->save();
        }
        // Другие файлы с устройства (для слабовидящих)
        $another_files_poor_vision = array_merge(json_decode($request->localDocuments1) ?? [], $request->localDocumentsStored1 ?? []);

        if ($another_files_poor_vision != $item_attachments->another_files_poor_vision) {

            $item_attachments->another_files_poor_vision = $another_files_poor_vision;

            $item_attachments->save();
        }

        $item_attachments->save();

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $course->id)->with('status', __('default.pages.lessons.lesson_update_success'));


    }

    public function createFinalTest($lang, Course $item)
    {
        // Проверка владельца курса
        if ($item->author_id == Auth::user()->id) {
            // Проверка существования финального теста
            $final_test = Lesson::where('course_id', '=', $item->id)->where('type', '=', 4)->first();
            if (empty($final_test)) {
                return view("app.pages.author.courses.create_final_test", [
                    "item" => $item
                ]);
                //Если курсовой работы нет
            } else {
                return redirect("/" . $lang . "/my-courses/course/" . $item->id)->with('error', __('default.pages.lessons.final_test_count_error_message'));
            }
            //Если автор не владелец курса
        } else {
            return redirect("/" . $lang . "/my-courses");
        }
    }

    public function storeFinalTest($lang, Request $request, Course $course)
    {
        $this->validate($request, [
            'duration' => 'required|numeric|gt:0'
        ]);

        $item = new Lesson;
        $item->course_id = $course->id;
        $item->index_number = 2;
        $item->type = 4; // Тип "Финальное тестирование"
        $item->end_lesson_type = 0; // Тип "Тест"
        $item->duration = $request->duration;
        $item->theory = $request->theory;
        $item->coursework_task = $request->coursework_task;

        if (($request->image != $item->image)) {
            File::delete(public_path($item->image));

            $item->image = $request->image;
        }

        $data = array(
            "questions" => array(),
            "passingScore" => $request->passingScore,
            "mixAnswers" => $request->mixAnswers
        );

        foreach ($request->questions as $key => $question) {
            $answers = $request->answers[$key];

            if (isset($request->isPictures[$key])) {
                foreach ($answers as $k => $answer) {
                    if (is_array(json_decode($answer))) {
                        $answers[$k] = json_decode($answer)[0];
                    }
                }
            }

            $data['questions'][] = array(
                'name' => $request->questions[$key],
                'is_pictures' => isset($request->isPictures[$key]),
                'answers' => $answers
            );
        }

        $item->practice = json_encode($data);

        $item->save();

        $item_attachments = new LessonAttachments;
        $item_attachments->lesson_id = $item->id;

        // Ссылки на видео курса
        $item_attachments->videos_link = json_encode($request->videos_link);

        // Ссылки на видео курса для слабовидящих
        if ($request->videos_poor_vision_link) {
            $item_attachments->videos_poor_vision_link = json_encode($request->videos_poor_vision_link);
        }

        // Видео с устройства
        if (($request->videos != $item_attachments->videos)) {
            File::delete(public_path($item_attachments->videos));

            $item_attachments->videos = $request->videos;
        }
        // Видео с устройства для слабовидящих
        if (($request->videos_poor_vision != $item_attachments->videos_poor_vision)) {
            File::delete(public_path($item_attachments->videos_poor_vision));

            $item_attachments->videos_poor_vision = $request->videos_poor_vision;
        }
        // Видео с устройства для лиц с нарушениями слуха
        if (($request->videos_poor_hearing != $item_attachments->videos_poor_hearing)) {
            File::delete(public_path($item_attachments->videos_poor_hearing));

            $item_attachments->videos_poor_hearing = $request->videos_poor_hearing;
        }
        // Аудио с устройства
        if (($request->audios != $item_attachments->audios)) {
            File::delete(public_path($item_attachments->audios));

            $item_attachments->audios = $request->audios;
        }
        // Аудио с устройства для слабовидящих
        if (($request->audios_poor_vision != $item_attachments->audios_poor_vision)) {
            File::delete(public_path($item_attachments->audios_poor_vision));

            $item_attachments->audios_poor_vision = $request->audios_poor_vision;
        }
        // Аудио с устройства для лиц с нарушениями слуха
        if (($request->audios_poor_hearing != $item_attachments->audios_poor_hearing)) {
            File::delete(public_path($item_attachments->audios_poor_hearing));

            $item_attachments->audios_poor_hearing = $request->audios_poor_hearing;
        }
        // Другие материалы
        if (($request->another_files != $item_attachments->another_files)) {
            File::delete(public_path($item_attachments->another_files));

            $item_attachments->another_files = $request->another_files;
        }
        // Другие материалы для слабовидящих
        if (($request->another_files_poor_vision != $item_attachments->another_files_poor_vision)) {
            File::delete(public_path($item_attachments->another_files_poor_vision));

            $item_attachments->another_files_poor_vision = $request->another_files_poor_vision;
        }
        // Другие материалы для лиц с нарушениями слуха
        if (($request->another_files_poor_hearing != $item_attachments->another_files_poor_hearing)) {
            File::delete(public_path($item_attachments->another_files_poor_hearing));

            $item_attachments->another_files_poor_hearing = $request->another_files_poor_hearing;
        }

        $item_attachments->save();

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $course->id)->with('status', __('default.pages.lessons.create_final_test_message'));
    }

    public function editFinalTest($lang, Course $course)
    {
        if ($course->author_id == Auth::user()->id) {
            $item = Lesson::where('course_id', '=', $course->id)->where('type', '=', 4)->first();

            return view("app.pages.author.courses.edit_final_test", [
                "course" => $course,
                "item" => $item
            ]);
        } else {
            return redirect("/" . app()->getLocale() . "/my-courses");
        }
    }

    public function updateFinalTest($lang, Request $request, Course $course)
    {

        $this->validate($request, [
            'duration' => 'required|numeric|gt:0'
        ]);

        $item = Lesson::where('course_id', '=', $course->id)->where('type', '=', 4)->first();
        $item->duration = $request->duration;
        $item->theory = $request->theory;

        if (($request->image != $item->image)) {
            File::delete(public_path($item->image));

            $item->image = $request->image;
        }

        $data = array(
            "questions" => array(),
            "passingScore" => $request->passingScore,
            "mixAnswers" => $request->mixAnswers
        );

        foreach ($request->questions as $key => $question) {
            $answers = $request->answers[$key];

            if (isset($request->isPictures[$key])) {
                foreach ($answers as $k => $answer) {
                    if (is_array(json_decode($answer))) {
                        $answers[$k] = json_decode($answer)[0];
                    }
                }
            }

            $data['questions'][] = array(
                'name' => $request->questions[$key],
                'is_pictures' => isset($request->isPictures[$key]),
                'answers' => $answers
            );
        }

        $item->practice = json_encode($data);

        $item->save();

        $item_attachments = LessonAttachments::where('lesson_id', '=', $item->id)->first();

        // Ссылки на видео курса
        $item_attachments->videos_link = json_encode($request->videos_link);
        // Ссылки на видео курса для слабовидящих
        if ($request->videos_poor_vision_link) {
            $item_attachments->videos_poor_vision_link = json_encode($request->videos_poor_vision_link);
        }
        // Ссылки на видео курса для лиц с нарушениями слуха
        if ($request->videos_poor_hearing_link) {
            $item_attachments->videos_poor_hearing_link = json_encode($request->videos_poor_hearing_link);
        }

        $videos = array_merge(json_decode($request->localVideo) ?? [], $request->localVideoStored ?? []);
        $audios = array_merge(json_decode($request->localAudio) ?? [], $request->localAudioStored ?? []);
        $another_files = array_merge(json_decode($request->localDocuments) ?? [], $request->localDocumentsStored ?? []);

        $videos_poor_vision = array_merge(json_decode($request->localVideo1) ?? [], $request->localVideoStored1 ?? []);
        $audios_poor_vision = array_merge(json_decode($request->localAudio1) ?? [], $request->localAudioStored1 ?? []);
        $another_files_poor_vision = array_merge(json_decode($request->localDocuments1) ?? [], $request->localDocumentsStored1 ?? []);

        $videos_poor_hearing = array_merge(json_decode($request->localVideo2) ?? [], $request->localVideoStored2 ?? []);
        $audios_poor_hearing = array_merge(json_decode($request->localAudio2) ?? [], $request->localAudioStored2 ?? []);
        $another_files_poor_hearing = array_merge(json_decode($request->localDocuments2) ?? [], $request->localDocumentsStored2 ?? []);

        // Видео с устройства
        if ($videos != $item_attachments->videos) {

            $item_attachments->videos = $videos;

            $item_attachments->save();
        }
        // Аудио с устройства
        if ($audios != $item_attachments->audios) {

            $item_attachments->audios = $audios;

            $item_attachments->save();
        }
        // Другие файлы с устройства
        if ($another_files != $item_attachments->another_files) {

            $item_attachments->another_files = $another_files;

            $item_attachments->save();
        }
        // Видео с устройства (для слабовидящих)
        if ($videos_poor_vision != $item_attachments->videos_poor_vision) {

            $item_attachments->videos_poor_vision = $videos_poor_vision;

            $item_attachments->save();
        }
        // Аудио с устройства (для слабовидящих)
        if ($audios_poor_vision != $item_attachments->audios_poor_vision) {

            $item_attachments->audios_poor_vision = $audios_poor_vision;

            $item_attachments->save();
        }
        // Другие файлы с устройства (для слабовидящих)
        if ($another_files_poor_vision != $item_attachments->another_files_poor_vision) {

            $item_attachments->another_files_poor_vision = $another_files_poor_vision;

            $item_attachments->save();
        }
        // Видео с устройства (для лиц с нарушениями слуха)
        if ($videos_poor_hearing != $item_attachments->videos_poor_hearing) {

            $item_attachments->videos_poor_hearing = $videos_poor_hearing;

            $item_attachments->save();
        }
        // Аудио с устройства (для лиц с нарушениями слуха)
        if ($audios_poor_hearing != $item_attachments->audios_poor_hearing) {

            $item_attachments->audios_poor_hearing = $audios_poor_hearing;

            $item_attachments->save();
        }
        // Другие файлы с устройства (для лиц с нарушениями слуха)
        if ($another_files_poor_hearing != $item_attachments->another_files_poor_hearing) {

            $item_attachments->another_files_poor_hearing = $another_files_poor_hearing;

            $item_attachments->save();
        }

        $item_attachments->save();

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $course->id)->with('status', __('default.pages.lessons.lesson_update_success'));
    }

    public function viewLesson($lang, Course $course, Lesson $lesson)
    {
        if ($course->author_id == Auth::user()->id) {
            //Конверт числа во время
            $time = FormatDate::convertMunitesToTime($lesson->duration);

            // Получить все файлы урока
            $lesson_attachments = LessonAttachments::whereId($lesson->id)->first();

            return view("app.pages.author.courses.lesson_preview.view_lesson", [
                "item" => $course,
                "lesson" => $lesson,
                "time" => $time,
                "lesson_attachments" => $lesson_attachments
            ]);
        } else {
            return redirect("/" . $lang . "/my-courses");
        }
    }

    public function deleteLessonForm($lang, Request $request, Course $course, Lesson $lesson)
    {
        Lesson::where('id', '=', $lesson->id)->delete();

        $theme = Theme::where('id', '=', $lesson->theme_id)->first();

        if ($theme != null) {
            $theme_lessons = Lesson::whereHas('themes', function ($q) use ($theme) {
                $q->where('themes.id', '=', $theme->id);
            })->orderBy('index_number', 'asc')->get();

            foreach ($theme_lessons as $key => $lesson) {
                $lesson->index_number = $key;
                $lesson->save();
            }
        }

        switch ($lesson->type) {
            case(3):
                return redirect("/" . app()->getLocale() . "/my-courses/course/" . $course->id)->with('status', __('default.pages.lessons.coursework_delete_success'));
            case(4):
                return redirect("/" . app()->getLocale() . "/my-courses/course/" . $course->id)->with('status', __('default.pages.lessons.final_test_delete_success'));
            default:
                return redirect("/" . app()->getLocale() . "/my-courses/course/" . $course->id)->with('status', __('default.pages.lessons.lesson_delete_success'));

        }

    }

    public function lessonFinished($lang, Request $request, Course $course, Lesson $lesson)
    {
        switch ($request->input('action')) {
            // Переход к домашнему заданию
            case 'homework':
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/author-homework');
            // Переход к тесту
            case 'test':
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/author-test');
                break;
            case 'coursework':
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/author-coursework');
                break;
            case 'final-test':
                return redirect('/' . $lang . '/course-catalog/course/' . $course->id . '/lesson-' . $lesson->id . '/author-test');
        }

        return redirect()->back();
    }

    public function testView($lang, Request $request, Course $course, Lesson $lesson)
    {
        if ($course->author_id == Auth::user()->id) {

            return view("app.pages.author.courses.lesson_preview.test_view_lesson", [
                "item" => $course,
                "lesson" => $lesson
            ]);
        } else {
            return redirect("/" . $lang . "/my-courses");
        }
    }

    public function homeWorkView($lang, Request $request, Course $course, Lesson $lesson)
    {
        if ($course->author_id == Auth::user()->id) {

            return view("app.pages.author.courses.lesson_preview.homework_view_lesson", [
                "item" => $course,
                "lesson" => $lesson
            ]);
        } else {
            return redirect("/" . $lang . "/my-courses");
        }
    }

    public function submitTest($lang, Request $request, Course $course, Lesson $lesson)
    {
        if ($course->author_id == Auth::user()->id) {

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

            return view("app.pages.author.courses.lesson_preview.test_results_view", [
                "item" => $course,
                "lesson" => $lesson,
                "results" => $test_results,
                "right_answers" => $right_answers
            ]);
        } else {
            return redirect("/" . $lang . "/my-courses");
        }
    }

    public function submitHomeWork($lang, Request $request, Course $course, Lesson $lesson)
    {
        return redirect("/" . $lang . "/my-courses/course/" . $course->id);
    }

    public function deleteTest($lang, Request $request, Course $course, Lesson $lesson)
    {
        Lesson::where('id', '=', $lesson->id)->delete();

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $course->id)->with('status', __('default.pages.lessons.lesson_delete_success'));
    }

    // Методы для таблицы курса
    public function deleteLesson(Request $request)
    {
        $lesson = Lesson::find($request->lesson_id);

        if ($lesson->theme_id != null) {
            $lesson->delete();

            $theme_lessons = Lesson::whereHas('themes', function ($q) use ($request) {
                $q->where('themes.id', '=', $request->theme_id);
            })->orderBy('index_number', 'asc')->get();

            foreach ($theme_lessons as $key => $lesson) {
                $lesson->index_number = $key;
                $lesson->save();
            }
        } else {
            $lesson->delete();

            $themes = Theme::whereCourseId($lesson->course_id)
                ->orderBy('index_number', 'asc')
                ->get();
            $untheme_lessons = Lesson::whereCourseId($lesson->course_id)
                ->whereThemeId(null)
                ->whereNotIn('type', [3, 4])
                ->orderBy('index_number', 'asc')
                ->get();
            $themes = $themes->merge($untheme_lessons)->sortBy('index_number')->values();

            foreach ($themes as $key => $theme) {
                $theme->index_number = $key;
                $theme->save();
            }
        }

        $messages = ["title" => __('default.pages.courses.delete_lesson_title'), "body" => __('default.pages.courses.delete_lesson_success')];

        return $messages;
    }

    public function moveLesson(Request $request)
    {
        $lesson_1 = Lesson::where('id', '=', $request->lesson_1_id)->first();
        $lesson_2 = Lesson::where('id', '=', $request->lesson_2_id)->first();

        [$lesson_1->index_number, $lesson_2->index_number] = [$lesson_2->index_number, $lesson_1->index_number];
        $lesson_1->save();
        $lesson_2->save();
    }
}

