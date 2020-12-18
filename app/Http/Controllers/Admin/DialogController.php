<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Dialog;
use App\Models\StudentCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class DialogController extends Controller
{
    public function main(Request $request)
    {
        $user = Auth::user();

        $query = Dialog::whereHas("members", function ($q) use ($user) {
            $q->where("user_id", "=", $user->id);
        })->with('members')->orderBy("updated_at", "desc");


        $items = $query->paginate();
        return view("admin.v2.pages.dialogs.index", [
            "items" => $items,
        ]);
    }

    public function view(Request $request, $lang, $id = null)
    {
        $user = $request->user();
        if (!$user) {
            abort(404);
        }
        $user = User::find($user->id);

        $respondent = User::where('id', '=', $id)->first();
        if (!$respondent) {
            abort(404);
        }

        $item = $user->dialogs()->whereHas("members", function ($q) use ($respondent) {
            $q->where("user_id", "=", $respondent->id);
        })->first();

        if (!$item) {

            if ($user->roles()->first()->slug == 'student') {
                $author_course = StudentCourse::where('student_id', '=', $user->id)->whereHas('course', function ($q) use ($id) {
                    $q->where('author_id', '=', $id);
                })->get();
                if (count($author_course) != 0) {
                    $item = new Dialog();
                    $item->save();

                    $item->members()->attach($user->id);
                    $item->members()->attach($respondent->id);
                } else {
                    abort(404);
                }
            }
        }


        return view("admin.v2.pages.dialogs.view", [
            "item" => $item
        ]);
    }

    public function viewDialog(Request $request, $lang, $id = null)
    {
        $user = $request->user();
        if (!$user) {
            abort(404);
        }
        $user = User::find($user->id);

        $respondent = User::where('id', '=', $id)->first();
        if (!$respondent) {
            abort(404);
        }

        $item = $user->dialogs()->whereHas("members", function ($q) use ($respondent) {
            $q->where("user_id", "=", $respondent->id);
        })->first();

        if (!$item) {

            if ($user->roles()->first()->slug == 'student') {
                $author_course = StudentCourse::where('student_id', '=', $user->id)->whereHas('course', function ($q) use ($id) {
                    $q->where('author_id', '=', $id);
                })->get();
                if (count($author_course) != 0) {
                    $item = new Dialog();
                    $item->save();

                    $item->members()->attach($user->id);
                    $item->members()->attach($respondent->id);
                } else {
                    abort(404);
                }
            }
        }

        $messages = $item->messages;

        return view("admin.v2.pages.dialogs.dialog_frame", [
            "item" => $item,
            "messages" => $messages
        ]);
    }

}
