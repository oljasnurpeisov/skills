<?php

namespace App\Http\Controllers\App\Author;

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

    public function storeLesson($lang, Request $request, Course $course, Theme $theme)
    {

        $last_id = Lesson::whereHas('themes', function ($q) use ($theme) {
                $q->where('themes.id', '=', $theme->id);
            })->orderBy('index_number', 'desc')->latest()->first()->index_number ?? 0;

        if (!empty($last_id)) {
            $last_id++;
        } else {
            $last_id = 0;
        }

        $item = new Lesson;
        $item->theme_id = $theme->id;
        $item->course_id = $course->id;
        $item->name = $request->name;
        $item->index_number = $last_id;
        if ($request->type == 'theory') {
            $item->type = 1;
        }else{
            $item->type = 2;
        }
        if($request->end_lesson_type == 'test'){
            $item->end_lesson_type = 0;
        }else{
            $item->end_lesson_type = 1;
        }
        $item->duration = $request->duration;
        $item->theory = $request->theory;

        if (($request->image != $item->image)) {
            File::delete(public_path($item->image));

            $item->image = $request->image;
        }

//        $item->test = $request->test;

        $item->save();

        $item_attachments = new LessonAttachments;
        $item_attachments->lesson_id = $item->id;

        // Ссылки на видео курса
        if ($request->videos_link != [null]) {
            $item_attachments->videos_link = json_encode($request->videos_link);
        }
        // Ссылки на видео курса для слабовидящих
        if ($request->videos_poor_vision_link != []) {
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

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $course->id)->with('status', __('default.pages.lessons.create_request_message'));
    }

    public function editLesson($lang, Course $course, Theme $theme, Lesson $lesson)
    {
//        $xml = XmlParser::load(url('https://iac2:Iac2007RBD@www.enbek.kz/feed/resume/cl_hard_skills.xml'));
//
//        $skills = $xml->parse([
//            'data' => ['uses' => 'row[field(::name=@)]'],
//        ]);

        $course_theme = $theme->courses()->first();
        $lesson_theme = $theme->with(["lessons" => function ($q) use ($lesson) {
            $q->where('lessons.id', '=', $lesson->id);
        }])->where('id', '=', $theme->id)->first();
//        $course = Course::where('id', '=', $course_theme->pivot->course_id)->first();
        $lessons_type = LessonsType::all();

        if (($course->author_id == Auth::user()->id) and (!empty($lesson_theme->lessons->first()->id) == $lesson->id)) {
            return view("app.pages.author.courses.edit_lesson", [
                "theme" => $theme,
                "item" => $lesson,
                "lessons_type" => $lessons_type,
                "course" => $course
            ]);
        } else {
            return redirect("/" . app()->getLocale() . "/my-courses");
        }


//        foreach (array_values($skills)[0] as $skill){
////            $item = new Skill;
////            $item->code_skill = $skill['codskill'];
////            $item->fl_check = $skill['fl_check'];
////            $item->name_ru = $skill['name_skill'];
////            $item->name_kk = $skill['name_skill_kz'];
////            $item->fl_show = $skill['fl_show'];
////            $item->uid = $skill['uid'];
////            $item->save();
//
//            $user = Skill::updateOrCreate([
//                'code_skill' => $skill['codskill']
//            ], [
//                'fl_check' => $skill['fl_check'],
//                'name_ru' => $skill['name_skill'],
//                'name_kk'=> $skill['name_skill_kz'],
//                'fl_show'=> $skill['fl_show'],
//                'uid'=> $skill['uid'],
//            ]);
//        }
////
//        return 1;

    }

    public function updateLesson($lang, Request $request, Lesson $item)
    {
        $item->name = $request->name;
        $item->type = $request->type;
        $item->end_lesson_type = $request->end_lesson_type;
        $item->duration = $request->duration;
        $item->theory = $request->theory;
        if ($request->youtube_link != [null]) {
            $item->youtube_link = json_encode($request->youtube_link);
        }
        $item->files = $request->another_files;
        $item->test = $request->test;

        if (!empty($request->image)) {
            File::delete(public_path($item->image));

            $imageName = time() . '.' . $request['image']->getClientOriginalExtension();
            $request['image']->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/images'), $imageName);

            $item->image = '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/images/' . $imageName;

        }

        if (!empty($request->video)) {
            File::delete(public_path($item->video));
            $videos = [];
            foreach ($request->video as $video) {
                $videoName = time() . '.' . $video->getClientOriginalExtension();
                $video->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/videos'), $videoName);
                array_push($videos, '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/videos/' . $videoName);
            }
            $item->video = json_encode($videos);
        }

        if (!empty($request->audio)) {
            File::delete(public_path($item->audio));
            $audios = [];
            foreach ($request->audio as $audio) {
                $audioName = time() . '.' . $audio->getClientOriginalExtension();
                $audio->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/audios'), $audioName);
                array_push($audios, '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/audios/' . $audioName);
            }
            $item->audio = json_encode($audios);
        }

        if ($request->hasFile('another_files')) {
            $names = [];
            foreach ($request->file('another_files') as $key => $file) {
                File::delete(public_path($file));
                $filename = time() . $key . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/files'), $filename);
                array_push($names, '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/files/' . $filename);

            }
            $item->files = json_encode($names);
        }

        $item->save();

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $request->course_id);
    }

    public function deleteLesson(Request $request)
    {
        Lesson::where('id', '=', $request->lesson_id)->delete();
    }

    public function moveLesson(Request $request)
    {
        $lesson_1 = Lesson::where('id', '=', $request->lesson_1_id)->first();
        $lesson_2 = Lesson::where('id', '=', $request->lesson_2_id)->first();

        [$lesson_1->index_number, $lesson_2->index_number] = [$lesson_2->index_number, $lesson_1->index_number];
        $lesson_1->save();
        $lesson_2->save();
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
        if ($request->videos_link != [null]) {
            $item_attachments->videos_link = json_encode($request->videos_link);
        }
        // Ссылки на видео курса для слабовидящих
        if ($request->videos_poor_vision_link != [null]) {
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

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $course->id)->with('status', __('default.pages.lessons.create_request_message'));
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
        $item = Lesson::where('course_id', '=', $course->id)->where('type', '=', 3)->first();
        $item->name = $request->name;
        $item->duration = $request->duration;
        $item->theory = $request->theory;
        if ($request->youtube_link != [null]) {
            $item->youtube_link = json_encode($request->youtube_link);
        }
        $item->files = $request->another_files;
        $item->test = $request->test;

        if (!empty($request->image)) {
            File::delete(public_path($item->image));

            $imageName = time() . '.' . $request['image']->getClientOriginalExtension();
            $request['image']->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/images'), $imageName);

            $item->image = '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/images/' . $imageName;

        }

        if (!empty($request->video)) {
            File::delete(public_path($item->video));
            $videos = [];
            foreach ($request->video as $video) {
                $videoName = time() . '.' . $video->getClientOriginalExtension();
                $video->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/videos'), $videoName);
                array_push($videos, '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/videos/' . $videoName);
            }
            $item->video = json_encode($videos);
        }

        if (!empty($request->audio)) {
            File::delete(public_path($item->audio));
            $audios = [];
            foreach ($request->audio as $audio) {
                $audioName = time() . '.' . $audio->getClientOriginalExtension();
                $audio->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/audios'), $audioName);
                array_push($audios, '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/audios/' . $audioName);
            }
            $item->audio = json_encode($audios);
        }

        if ($request->hasFile('another_files')) {
            $names = [];
            foreach ($request->file('another_files') as $key => $file) {
                File::delete(public_path($file));
                $filename = time() . $key . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/files'), $filename);
                array_push($names, '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/files/' . $filename);

            }
            $item->files = json_encode($names);
        }

        $item->save();

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $request->course_id)->with('status', __('default.pages.lessons.lesson_update_success'));
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
}