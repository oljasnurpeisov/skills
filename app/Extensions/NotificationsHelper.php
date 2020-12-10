<?php

namespace App\Extensions;

use App\Models\Notification;

class NotificationsHelper
{

    public static function createNotification($name, $course_id, $recipient, $type = 0, $message = null)
    {
        $notification = new Notification;
        $notification->name = $name;
        $notification->course_id = $course_id;
        $notification->type = $type;
        $notification->message = $message;
        $notification->save();

        $notification->users()->sync([$recipient]);
    }

}
