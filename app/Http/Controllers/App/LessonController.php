<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
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
        if ($item->users()->first()->id == Auth::user()->id) {
            return view("app.pages.page.courses.create_lesson", [
                "item" => $item,
                "theme" => $theme,
                "lessons_type" => $lessons_type
            ]);
        } else {
            return redirect("/" . app()->getLocale() . "/my-courses");
        }
    }

    public function storeLesson(Request $request)
    {
        $last_id = Lesson::whereHas('themes', function($q) use ($request){
            $q->where('themes.id', '=', $request->theme_id);
        })->orderBy('index_number', 'desc')->latest()->first()->index_number ?? 0;

        if (!empty($last_id)) {
            $last_id++;
        } else {
            $last_id = 1;
        }

        $item = new Lesson;
        $item->name = $request->name;
        $item->index_number = $last_id;
        $item->type = $request->type;
        $item->end_lesson_type = $request->end_lesson_type;
        $item->duration = $request->duration;
        $item->theory = $request->theory;
        $item->youtube_link = $request->youtube_link;
        $item->files = $request->another_files;
        $item->test = $request->test;

        if (!empty($request->image)) {
            File::delete(public_path($item->image));

            $imageName = time() . '.' . $request['image']->getClientOriginalExtension();
            $request['image']->move(public_path('images/courses_images'), $imageName);
            $item->image = '/images/courses_images/' . $imageName;
        }

        if (!empty($request->video)) {
            File::delete(public_path($item->video));

            $videoName = time() . '.' . $request['video']->getClientOriginalExtension();
            $request['video']->move(public_path('files/courses_videos'), $videoName);
            $item->video = '/files/courses_videos/' . $videoName;
        }

        if (!empty($request->audio)) {
            File::delete(public_path($item->audio));

            $audioName = time() . '.' . $request['audio']->getClientOriginalExtension();
            $request['audio']->move(public_path('files/courses_audio'), $audioName);
            $item->audio = '/files/courses_audio/' . $audioName;
        }

        if ($request->hasFile('another_files')) {
            $names = [];
            foreach ($request->file('another_files') as $key => $file) {
                File::delete(public_path($file));
                $filename = time() . $key . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('files/lesson_files'), $filename);
                array_push($names, '/files/lesson_files/' . $filename);

            }
            $item->files = json_encode($names);
        }

        $item->save();

        $item->themes()->sync([$request->theme_id]);

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $request->course_id);
    }

    public function editLesson($lang, Theme $theme, Lesson $lesson)
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
        $course = Course::where('id', '=', $course_theme->pivot->course_id)->first();
        $lessons_type = LessonsType::all();

        if (($course->users()->first()->id == Auth::user()->id) and (!empty($lesson_theme->lessons->first()->id) == $lesson->id)) {
            return view("app.pages.page.courses.edit_lesson", [
                "theme" => $theme,
                "item" => $lesson,
                "lessons_type" => $lessons_type
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
        $item->youtube_link = $request->youtube_link;
        $item->files = $request->another_files;
        $item->test = $request->test;

        if (!empty($request->image)) {
            File::delete(public_path($item->image));

            $imageName = time() . '.' . $request['image']->getClientOriginalExtension();
            $request['image']->move(public_path('images/courses_images'), $imageName);
            $item->image = '/images/courses_images/' . $imageName;
        }

        if (!empty($request->video)) {
            File::delete(public_path($item->video));

            $videoName = time() . '.' . $request['video']->getClientOriginalExtension();
            $request['video']->move(public_path('files/courses_videos'), $videoName);
            $item->video = '/files/courses_videos/' . $videoName;
        }

        if (!empty($request->audio)) {
            File::delete(public_path($item->audio));

            $audioName = time() . '.' . $request['audio']->getClientOriginalExtension();
            $request['audio']->move(public_path('files/courses_audio'), $audioName);
            $item->audio = '/files/courses_audio/' . $audioName;
        }

        if ($request->hasFile('another_files')) {
            $names = [];
            foreach ($request->file('another_files') as $key => $file) {
                File::delete(public_path($file));
                $filename = time() . $key . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('files/lesson_files'), $filename);
                array_push($names, '/files/lesson_files/' . $filename);

            }
            $item->files = json_encode($names);
        }

        $item->save();

        return redirect("/" . app()->getLocale() . "/my-courses/course/" . $request->course_id);
    }

    public function deleteLesson($lang, Request $request)
    {
        Lesson::where('id', '=', $request->lesson_id)->delete();
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

    public function moveupLesson($lang, Request $request)
    {
        $previous_lesson = Lesson::where('index_number', '<', $request->lesson_index)->whereHas('themes', function($q) use ($request){
            $q->where('themes.id', '=', $request->theme_id);
        })->first();
        $current_lesson = Lesson::where('id', '=', $request->lesson_id)->where('index_number', '=', $request->lesson_index)->first();

        $current_lesson->index_number = $previous_lesson->index_number;
        $previous_lesson->index_number = $request->lesson_index;
        $previous_lesson->save();
        $current_lesson->save();

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

    public function movedownLesson($lang, Request $request)
    {
        $next_lesson = Lesson::where('index_number', '>', $request->lesson_index)->whereHas('themes', function($q) use ($request){
        $q->where('themes.id', '=', $request->theme_id);
    })->first();
        $current_lesson = Lesson::where('id', '=', $request->lesson_id)->where('index_number', '=', $request->lesson_index)->first();

        $current_lesson->index_number = $next_lesson->index_number;
        $next_lesson->index_number = $request->lesson_index;
        $next_lesson->save();
        $current_lesson->save();

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