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
        $untheme_lessons = Lesson::whereCourseId($request->course_id)
            ->whereThemeId(null)
            ->whereNotIn('type', [3,4])
            ->orderBy('index_number', 'asc')
            ->get();

        $themes = Theme::whereCourseId($request->course_id)
            ->orderBy('index_number', 'asc')
            ->get();

        $themes = $themes->merge($untheme_lessons)
            ->sortBy('index_number')
            ->last();

        $last_id = $themes->index_number;

        if ($last_id) {
            $index = $last_id + 1;
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
        $theme = Theme::find($request->theme_id);

        $theme->delete();

        $themes = Theme::whereCourseId($theme->course_id)
            ->orderBy('index_number', 'asc')
            ->get();
        $untheme_lessons = Lesson::whereCourseId($theme->course_id)
            ->whereThemeId(null)
            ->whereNotIn('type', [3,4])
            ->orderBy('index_number', 'asc')
            ->get();
        $themes = $themes->merge($untheme_lessons)->sortBy('index_number')->values();

        foreach ($themes as $key => $theme) {
            $theme->index_number = $key;
            $theme->save();
        }

        $messages = ["title" => __('default.pages.courses.delete_theme_title'), "body" => __('default.pages.courses.delete_theme_success')];

        return $messages;
    }

    public function moveTheme(Request $request)
    {
        $theme_1 = Theme::where('id', '=', $request->theme_1_id)->first();
        $theme_2 = Theme::where('id', '=', $request->theme_2_id)->first();

        [$theme_1->index_number, $theme_2->index_number] = [$theme_2->index_number, $theme_1->index_number];
        $theme_1->save();
        $theme_2->save();
    }

    public function moveItem(Request $request)
    {
        $item_1_id = $request->item_1_id;
        $item_2_id = $request->item_2_id;
        $item_1_type = $request->item_1_type;
        $item_2_type = $request->item_2_type;

        if ($item_1_type == 'theme') {
            $item_1 = Theme::where('id', '=', $item_1_id)->first();
        } else {
            $item_1 = Lesson::where('id', '=', $item_1_id)->where('theme_id', '=', null)->first();
        }

        if ($item_2_type == 'theme') {
            $item_2 = Theme::where('id', '=', $item_2_id)->first();
        } else {
            $item_2 = Lesson::where('id', '=', $item_2_id)->where('theme_id', '=', null)->first();
        }

        /** Добавить тут проверку автора на владение курса */

        if ($item_1 and $item_2) {
            [$item_1->index_number, $item_2->index_number] = [$item_2->index_number, $item_1->index_number];
            $item_1->save();
            $item_2->save();

            return 200;
        } else {
            return 'Не удалось выполнить запрос, один или несколько объектов не были найдены';
        }

    }
}