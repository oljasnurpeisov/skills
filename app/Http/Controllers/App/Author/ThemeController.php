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
    public function createTheme($lang, Request $request)
    {
//        $last_id = Theme::with(["courses" => function ($q) use ($request) {
//            $q->where('courses.id', '=', $request->theme_id);
//        }])->orderBy('index_number', 'desc')->latest()->first()->index_number;

        $last_id = Theme::whereHas('courses', function($q) use ($request){
                $q->where('courses.id', '=', $request->course_id);
            })->orderBy('index_number', 'desc')->latest()->first()->index_number ?? 0;

        if (!empty($last_id)) {
            $last_id++;
        } else {
            $last_id = 1;
        }

        $theme = new Theme;
        $theme->name = $request->theme_name;
        $theme->index_number = $last_id;
        $theme->save();

        $theme->courses()->sync([$request->course_id]);

        $course = Course::where('id', '=', $request->course_id)->first();
        $themes = $course->themes()->orderBy('index_number', 'asc')->get();
        $table_content = '<table class="table table-striped" id="themes_table">
            <tbody>';
        foreach ($themes as $theme) {
            $content = '<tr>
            <td>' . $theme->name . '</td>
            <td><a href="/' . $lang . '/my-courses/course/' . $request->course_id . '/theme-' . $theme->id . '/create-lesson"
                                           class="btn btn-primary">+</a>
            <button type="button" theme-id="' . $theme->id . '" theme-name="' . $theme->name . '" class="btn btn-warning" data-toggle="modal" data-target=".editThemeModal"><i class="fa fa-pencil"></i></button>
            <button type="button" class="btn btn-danger deleteThemeBtn"><i class="fa fa-trash"></i></button>
            <button type="button" class="btn btn-info moveUpThemeBtn"><i
                                                        class="fa fa-arrow-up"></i></button>
                                            <button type="button" class="btn btn-info moveDownThemeBtn"><i
                                                        class="fa fa-arrow-down"></i></button>
            <td hidden>' . $theme->id . '</td><td hidden>'.$theme->index_number.'</td>
            </tr>';
            foreach ($theme->lessons()->orderBy('index_number', 'asc')->get() as $key => $lesson) {
                $content .= '<tr><td></td><td>' . $lesson->name . ' <a href="/{{$lang}}/my-courses/theme-{{$theme->id}}/edit-lesson-{{$lesson->id}}"
                                                   class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                                                <button type="button" class="btn btn-danger deleteLessonBtn"><i
                                                            class="fa fa-trash"></i></button>
                                                            <button type="button" class="btn btn-info moveUpLessonBtn"><i
                                                            class="fa fa-arrow-up"></i></button>
                                                <button type="button" class="btn btn-info moveDownLessonBtn"><i
                                                            class="fa fa-arrow-down"></i></button></td><td hidden>' . $lesson->id . '</td><td hidden>' . $lesson->index_number . '</td><td hidden>'.$theme->id.'</td></tr>';
            }
            $table_content .= $content;
        }
        $table_content .= '</tbody>
                        </table>';

//        $themes = Theme::where('course_id', '=', $request->course_id)->get();

        return $table_content;
    }

    public function editTheme($lang, Request $request, Theme $item)
    {
        $item->name = $request->theme_name;
        $item->save();

        $course = Course::where('id', '=', $request->course_id)->first();
        $themes = $course->themes()->orderBy('index_number', 'asc')->get();
        $table_content = '<table class="table table-striped" id="themes_table">
            <tbody>';
        foreach ($themes as $theme) {
            $content = '<tr>
            <td>' . $theme->name . '</td>
            <td><a href="/' . $lang . '/my-courses/course/' . $request->course_id . '/theme-' . $theme->id . '/create-lesson"
                                           class="btn btn-primary">+</a>
            <button type="button" theme-id="' . $theme->id . '" theme-name="' . $theme->name . '" class="btn btn-warning" data-toggle="modal" data-target=".editThemeModal"><i class="fa fa-pencil"></i></button>
            <button type="button" class="btn btn-danger deleteThemeBtn"><i class="fa fa-trash"></i></button>
            <button type="button" class="btn btn-info moveUpThemeBtn"><i
                                                        class="fa fa-arrow-up"></i></button>
                                            <button type="button" class="btn btn-info moveDownThemeBtn"><i
                                                        class="fa fa-arrow-down"></i></button>
            <td hidden>' . $theme->id . '</td><td hidden>'.$theme->index_number.'</td>
            </tr>';
            foreach ($theme->lessons()->orderBy('index_number', 'asc')->get() as $key => $lesson) {
                $content .= '<tr><td></td><td>' . $lesson->name . ' <a href="/{{$lang}}/my-courses/theme-{{$theme->id}}/edit-lesson-{{$lesson->id}}"
                                                   class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                                                <button type="button" class="btn btn-danger deleteLessonBtn"><i
                                                            class="fa fa-trash"></i></button>
                                                            <button type="button" class="btn btn-info moveUpLessonBtn"><i
                                                            class="fa fa-arrow-up"></i></button>
                                                <button type="button" class="btn btn-info moveDownLessonBtn"><i
                                                            class="fa fa-arrow-down"></i></button></td><td hidden>' . $lesson->id . '</td><td hidden>' . $lesson->index_number . '</td><td hidden>'.$theme->id.'</td></tr>';
            }
            $table_content .= $content;
        }
        $table_content .= '</tbody>
                        </table>';

        return $table_content;
    }

    public function deleteTheme($lang, Request $request)
    {
        Theme::where('id', '=', $request->theme_id)->delete();
        $course = Course::where('id', '=', $request->course_id)->first();
        $themes = $course->themes()->orderBy('index_number', 'asc')->get();
        $table_content = '<table class="table table-striped" id="themes_table">
            <tbody>';
        foreach ($themes as $theme) {
            $content = '<tr>
            <td>' . $theme->name . '</td>
            <td><a href="/' . $lang . '/my-courses/course/' . $request->course_id . '/theme-' . $theme->id . '/create-lesson"
                                           class="btn btn-primary">+</a>
            <button type="button" theme-id="' . $theme->id . '" theme-name="' . $theme->name . '" class="btn btn-warning" data-toggle="modal" data-target=".editThemeModal"><i class="fa fa-pencil"></i></button>
            <button type="button" class="btn btn-danger deleteThemeBtn"><i class="fa fa-trash"></i></button>
            <button type="button" class="btn btn-info moveUpThemeBtn"><i
                                                        class="fa fa-arrow-up"></i></button>
                                            <button type="button" class="btn btn-info moveDownThemeBtn"><i
                                                        class="fa fa-arrow-down"></i></button>
            <td hidden>' . $theme->id . '</td><td hidden>'.$theme->index_number.'</td>
            </tr>';
            foreach ($theme->lessons()->orderBy('index_number', 'asc')->get() as $key => $lesson) {
                $content .= '<tr><td></td><td>' . $lesson->name . ' <a href="/{{$lang}}/my-courses/theme-{{$theme->id}}/edit-lesson-{{$lesson->id}}"
                                                   class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                                                <button type="button" class="btn btn-danger deleteLessonBtn"><i
                                                            class="fa fa-trash"></i></button>
                                                            <button type="button" class="btn btn-info moveUpLessonBtn"><i
                                                            class="fa fa-arrow-up"></i></button>
                                                <button type="button" class="btn btn-info moveDownLessonBtn"><i
                                                            class="fa fa-arrow-down"></i></button></td><td hidden>' . $lesson->id . '</td><td hidden>' . $lesson->index_number . '</td><td hidden>'.$theme->id.'</td></tr>';
            }
            $table_content .= $content;
        }
        $table_content .= '</tbody>
                        </table>';

        return $table_content;
    }

    public function moveupTheme($lang, Request $request)
    {
        $previous_theme = Theme::where('index_number', '<', $request->theme_index)->whereHas('courses', function($q) use ($request){
            $q->where('courses.id', '=', $request->course_id);
        })->first();
        $current_theme = Theme::where('id', '=', $request->theme_id)->where('index_number', '=', $request->theme_index)->first();

        $current_theme->index_number = $previous_theme->index_number;
        $previous_theme->index_number = $request->theme_index;
        $previous_theme->save();
        $current_theme->save();

        $course = Course::where('id', '=', $request->course_id)->first();
        $themes = $course->themes()->orderBy('index_number', 'asc')->get();
        $table_content = '<table class="table table-striped" id="themes_table">
            <tbody>';
        foreach ($themes as $theme) {
            $content = '<tr>
            <td>' . $theme->name . '</td>
            <td><a href="/' . $lang . '/my-courses/course/' . $request->course_id . '/theme-' . $theme->id . '/create-lesson"
                                           class="btn btn-primary">+</a>
            <button type="button" theme-id="' . $theme->id . '" theme-name="' . $theme->name . '" class="btn btn-warning" data-toggle="modal" data-target=".editThemeModal"><i class="fa fa-pencil"></i></button>
            <button type="button" class="btn btn-danger deleteThemeBtn"><i class="fa fa-trash"></i></button>
            <button type="button" class="btn btn-info moveUpThemeBtn"><i
                                                        class="fa fa-arrow-up"></i></button>
                                            <button type="button" class="btn btn-info moveDownThemeBtn"><i
                                                        class="fa fa-arrow-down"></i></button>
            <td hidden>' . $theme->id . '</td><td hidden>'.$theme->index_number.'</td>
            </tr>';
            foreach ($theme->lessons()->orderBy('index_number', 'asc')->get() as $key => $lesson) {
                $content .= '<tr><td></td><td>' . $lesson->name . ' <a href="/{{$lang}}/my-courses/theme-{{$theme->id}}/edit-lesson-{{$lesson->id}}"
                                                   class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                                                <button type="button" class="btn btn-danger deleteLessonBtn"><i
                                                            class="fa fa-trash"></i></button>
                                                            <button type="button" class="btn btn-info moveUpLessonBtn"><i
                                                            class="fa fa-arrow-up"></i></button>
                                                <button type="button" class="btn btn-info moveDownLessonBtn"><i
                                                            class="fa fa-arrow-down"></i></button></td><td hidden>' . $lesson->id . '</td><td hidden>' . $lesson->index_number . '</td><td hidden>'.$theme->id.'</td></tr>';
            }
            $table_content .= $content;
        }
        $table_content .= '</tbody>
                        </table>';

        return $table_content;
    }

    public function movedownTheme($lang, Request $request)
    {
        $next_theme = Theme::where('index_number', '>', $request->theme_index)->whereHas('courses', function($q) use ($request){
            $q->where('courses.id', '=', $request->course_id);
        })->first();
        $current_theme = Theme::where('id', '=', $request->theme_id)->where('index_number', '=', $request->theme_index)->first();

        $current_theme->index_number = $next_theme->index_number;
        $next_theme->index_number = $request->theme_index;
        $next_theme->save();
        $current_theme->save();

        $course = Course::where('id', '=', $request->course_id)->first();
        $themes = $course->themes()->orderBy('index_number', 'asc')->get();
        $table_content = '<table class="table table-striped" id="themes_table">
            <tbody>';
        foreach ($themes as $theme) {
            $content = '<tr>
            <td>' . $theme->name . '</td>
            <td><a href="/' . $lang . '/my-courses/course/' . $request->course_id . '/theme-' . $theme->id . '/create-lesson"
                                           class="btn btn-primary">+</a>
            <button type="button" theme-id="' . $theme->id . '" theme-name="' . $theme->name . '" class="btn btn-warning" data-toggle="modal" data-target=".editThemeModal"><i class="fa fa-pencil"></i></button>
            <button type="button" class="btn btn-danger deleteThemeBtn"><i class="fa fa-trash"></i></button>
            <button type="button" class="btn btn-info moveUpThemeBtn"><i
                                                        class="fa fa-arrow-up"></i></button>
                                            <button type="button" class="btn btn-info moveDownThemeBtn"><i
                                                        class="fa fa-arrow-down"></i></button>
            <td hidden>' . $theme->id . '</td><td hidden>'.$theme->index_number.'</td>
            </tr>';
            foreach ($theme->lessons()->orderBy('index_number', 'asc')->get() as $key => $lesson) {
                $content .= '<tr><td></td><td>' . $lesson->name . ' <a href="/{{$lang}}/my-courses/theme-{{$theme->id}}/edit-lesson-{{$lesson->id}}"
                                                   class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                                                <button type="button" class="btn btn-danger deleteLessonBtn"><i
                                                            class="fa fa-trash"></i></button>
                                                            <button type="button" class="btn btn-info moveUpLessonBtn"><i
                                                            class="fa fa-arrow-up"></i></button>
                                                <button type="button" class="btn btn-info moveDownLessonBtn"><i
                                                            class="fa fa-arrow-down"></i></button></td><td hidden>' . $lesson->id . '</td><td hidden>' . $lesson->index_number . '</td><td hidden>'.$theme->id.'</td></tr>';
            }
            $table_content .= $content;
        }
        $table_content .= '</tbody>
                        </table>';

        return $table_content;
    }
}