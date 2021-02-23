<?php

namespace App\Http\Controllers\Admin;

use App\Extensions\NotificationsHelper;
use App\Models\Course;
use App\Models\Dialog;
use App\Models\Message;
use App\Models\StudentCourse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class DialogController extends Controller
{
    public function main(Request $request)
    {
        $tech_support_user = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'tech_support');
        })->first();

        $query = Dialog::whereHas("members", function ($q) use ($tech_support_user) {
            $q->where("user_id", "=", $tech_support_user->id);
        })->with('members')->orderBy("updated_at", "desc");


        $items = $query->paginate();
        return view("admin.v2.pages.dialogs.index", [
            "items" => $items,
        ]);
    }

    public function view(Request $request, $lang, $id = null)
    {

        $user = $tech_support_user = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'tech_support');
        })->first();

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
        $user = $tech_support_user = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'tech_support');
        })->first();

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

    public function save(Request $request, $lang, Dialog $dialog)
    {
        $user = $tech_support_user = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'tech_support');
        })->first();

        $item = new Message;
        $item->dialog_id = $dialog->id;
        $item->sender_id = $user->id;
        $item->message = $request->get("message", "");
        $item->save();

        $dialog_item = Dialog::whereId($dialog->id)->first();
        if ($dialog_item) {
            $dialog_item->updated_at = Carbon::now();
            $dialog_item->save();
        }

        $opponent = $dialog->members()->where('user_id', '!=', $user->id)->first();

        $notification_data = [
            'dialog_opponent_id' => $user->id
        ];
        $notification_name = 'notifications.new_message';
        NotificationsHelper::createNotification($notification_name, null, $opponent->id, 0, $notification_data);

        if ($request->get("format") == "json") {
            return Response::json([
                "success" => true,
                "dialogId" => $dialog->id,
                "messageId" => $item->id,
            ], 200);
        }

        return back();
    }

}
