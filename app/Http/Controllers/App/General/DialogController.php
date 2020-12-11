<?php

namespace App\Http\Controllers\App\General;

use App\Extensions\NotificationsHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Dialog;
use App\Models\Message;
use App\Models\StudentCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;


class DialogController extends Controller
{
    public function index(){
        $user = Auth::user();

        $query = Dialog::whereHas("members", function ($q) use ($user) {
            $q->where("user_id", "=", $user->id);
        })->with('members')->orderBy("updated_at", "desc");

        $items = $query->paginate();

        return view("app.pages.general.dialogs.index", [
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

            if($user->roles()->first()->slug == 'student'){
                $author_course = StudentCourse::where('student_id', '=', $user->id)->whereHas('course', function($q) use($id){
                    $q->where('author_id', '=', $id);
                })->get();
                if(count($author_course) != 0){
                    $item = new Dialog();
                    $item->save();

                    $item->members()->attach($user->id);
                    $item->members()->attach($respondent->id);
                }else{
                    abort(404);
                }
            }
        }

        $messages = $item->messages;

        return view("app.pages.general.dialogs.view", [
            "item" => $item,
            "messages" => $messages
        ]);
    }

    public function save(Request $request, $lang, Dialog $dialog)
    {
        $item = new Message;
        $item->dialog_id = $dialog->id;
        $item->sender_id = $request->user()->id;
        $item->message = $request->get("message", "");
        $item->save();

        $dialog_item = Dialog::whereId($dialog->id)->first();
        if ($dialog_item) {
            $dialog_item->updated_at = Carbon::now();
            $dialog_item->save();
        }

        $opponent = $dialog->members()->where('user_id', '!=', Auth::user()->id)->first();

        $notification_data = [
          'dialog_opponent_id' => Auth::user()->id
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
