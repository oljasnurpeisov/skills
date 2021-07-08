<?php

namespace App\Extensions;

use App\Models\Notification;
use App\Models\User;
use Edujugon\PushNotification\PushNotification;

class NotificationsHelper
{

    public static function createNotification($name, $course_id, $recipient, $type = 0, $data = null)
    {
        $notification = new Notification;
        $notification->name = $name;
        $notification->course_id = $course_id;
        $notification->type = $type;
        if ($data !== null) {
            $notification->data = json_encode([$data]);
        }
        $notification->save();

        $notification->users()->sync([$recipient]);

        self::createPush($notification, $recipient);
    }

    public static function createPush($notification, $recipient)
    {
        $user = User::whereId($recipient)->first();

        $opponent = User::whereId(json_decode($notification->data)[0]->dialog_opponent_id ?? 0)->first();
        $notification = strip_tags(trans($notification->name, ['course_name' => '"'. optional($notification->course)->name .'"', 'course_id' => optional($notification->course)->id, 'opponent_id' => json_decode($notification->data)[0]->dialog_opponent_id ?? 0, 'reject_message' => json_decode($notification->data)[0]->course_reject_message ?? '','user_name' => $opponent ? ($opponent->hasRole('author') ? $opponent->author_info->name . ' ' . $opponent->author_info->surname : $opponent->student_info->name ??  $opponent->name) : '']));

        if (!empty($user) and $user->ios_token != null) {
            $push = new PushNotification('apn');
            $response = $push->setMessage([
                'aps' => [
                    'alert' => [
                        'title' => 'Academ Enbek',
                        'body' => $notification
                    ],
                    'sound' => 'default',
                    'badge' => 1

                ],
            ])
                ->setDevicesToken([$user->ios_token])
                ->send();
        }
        if (!empty($user) and $user->android_token != null) {
            $push = new PushNotification('fcm');
            $push->setMessage([
                'notification' => [
                    'title' => 'Academ Enbek',
                    'body' => $notification,
                    'sound' => 'default'
                ],
            ])
                ->setDevicesToken([$user->android_token])
                ->send();
        }

    }

}
