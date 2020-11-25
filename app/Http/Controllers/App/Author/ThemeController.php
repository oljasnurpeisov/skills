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

    public function createTheme(Request $request){
        $last_id = Theme::whereHas('courses', function($q) use ($request){
                $q->where('courses.id', '=', $request->course_id);
            })->orderBy('index_number', 'desc')->latest()->first()->index_number ?? 0;

        if (!empty($last_id)) {
            $last_id++;
        } else {
            $last_id = 0;
        }

        $theme = new Theme;
        $theme->course_id = $request->course_id;
        $theme->name = $request->name;
        $theme->index_number = $last_id;
        $theme->save();

        return $theme->id;
    }

    public function editTheme($lang, Request $request)
    {
        $theme = Theme::where('id', '=', $request->theme_id)->first();

        $theme->name = $request->theme_name;
        $theme->save();

    }

    public function deleteTheme(Request $request)
    {
        Theme::where('id', '=', $request->theme_id)->delete();

        $course_themes = Theme::whereHas('courses', function($q) use ($request){
            $q->where('courses.id', '=', $request->course_id);
        })->orderBy('index_number', 'asc')->get();

        foreach ($course_themes as $key => $theme){
            $theme->index_number = $key;
            $theme->save();
        }
    }

    public function moveTheme(Request $request){
        $theme_1 = Theme::where('id', '=', $request->theme_1_id)->first();
        $theme_2 = Theme::where('id', '=', $request->theme_2_id)->first();

        [$theme_1->index_number, $theme_2->index_number] = [$theme_2->index_number, $theme_1->index_number];
        $theme_1->save();
        $theme_2->save();
    }
}