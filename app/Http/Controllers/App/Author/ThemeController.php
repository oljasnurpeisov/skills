<?php

namespace App\Http\Controllers\App\Author;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Theme;
use App\Models\Type_of_ownership;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class ThemeController extends Controller
{

    public function createTheme(Request $request)
    {
        $last_id = Theme::whereHas('courses', function ($q) use ($request) {
            $q->where('courses.id', '=', $request->course_id);
        })->orderBy('index_number', 'desc')->latest()->first();

        if ($last_id) {
            $index = $last_id->index_number + 1;
        } else {
            $index = 0;
        }

        $theme = new Theme;
        $theme->course_id = $request->course_id;
        $theme->name = $request->name;
        $theme->index_number = $index;
        $theme->save();

        return $theme->id;
    }

    public function editTheme(Request $request)
    {
        $theme = Theme::where('id', '=', $request->theme_id)->first();

        $theme->name = $request->theme_name;
        $theme->save();

        $messages = ["title" => __('default.pages.courses.edit_theme_title'), "body" => __('default.pages.courses.edit_theme_success')];

        return $messages;
    }

    public function deleteTheme(Request $request)
    {
        Theme::where('id', '=', $request->theme_id)->delete();

        $course_themes = Theme::whereHas('courses', function ($q) use ($request) {
            $q->where('courses.id', '=', $request->course_id);
        })->orderBy('index_number', 'asc')->get();

        foreach ($course_themes as $key => $theme) {
            $theme->index_number = $key;
            $theme->save();
        }


        $messages = ["title" => __('default.pages.courses.delete_theme_title'), "body" => __('default.pages.courses.delete_theme_success')];

        return $messages;
    }

    public function moveTheme(Request $request)
    {
//        return $request;
        $theme_1 = Theme::where('id', '=', $request->theme_1_id)->first();
        $theme_2 = Theme::where('id', '=', $request->theme_2_id)->first();

//        // Если это не тема, то вернуть урок без темы
//        if (!$theme_1) {
//            $theme_1 = Lesson::where('id', '=', $request->theme_1_id)->where('theme_id', '=', null)->first();
//        }
//        if (!$theme_2) {
//            $theme_2 = Lesson::where('id', '=', $request->theme_2_id)->where('theme_id', '=', null)->first();
//        }

        [$theme_1->index_number, $theme_2->index_number] = [$theme_2->index_number, $theme_1->index_number];
        $theme_1->save();
        $theme_2->save();
    }

    public function moveItem(Request $request)
    {
        $theme_1 = $request->theme_1_id;
        $theme_2 = $request->theme_2_id;
        $lesson_1 = $request->lesson_1_id;
        $lesson_2 = $request->lesson_2_id;
        $items = [];

        if ($theme_1) {
            $items[] = Theme::where('id', '=', $request->theme_1_id)->first();
        }
        if ($theme_2) {
            $items[] = Theme::where('id', '=', $request->theme_2_id)->first();
        }
        if ($lesson_1) {
            $items[] = Lesson::where('id', '=', $request->lesson_1_id)->where('theme_id', '=', null)->first();
        }
        if ($lesson_2) {
            $items[] = Lesson::where('id', '=', $request->lesson_2_id)->where('theme_id', '=', null)->first();
        }

        /** Добавить тут проверку автора на владение курса */

        if (count($items) == 2) {
            if ($items[0] and $items[1]) {
                [$items[0]->index_number, $items[1]->index_number] = [$items[1]->index_number, $items[0]->index_number];
                $items[0]->save();
                $items[1]->save();

                return 200;
            } else {
                return 'Не удалось выполнить запрос, один или несколько объектов не были найдены';
            }
        } else {
            return 'Для выполнения запроса необходимо передать 2 объекта';
        }

    }
}