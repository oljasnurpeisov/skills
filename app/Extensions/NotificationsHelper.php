<?php

namespace App\Extensions;

use App\Models\Notification;

class NotificationsHelper
{

    public static function createNotification($name, $course_id, $recipient, $type = 0, $data = null)
    {
        $notification = new Notification;
        $notification->name = $name;
        $notification->course_id = $course_id;
        $notification->type = $type;
        $notification->data = json_encode([$data]);
        $notification->save();

        $notification->users()->sync([$recipient]);
    }

}
