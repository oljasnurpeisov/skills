<?php

namespace Services\Notifications;

use App\Extensions\NotificationsHelper;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $course_id;

    /**
     * @var int
     */
    private $user_id;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $lang;
    /**
     * @var string
     */
    private $subject;


    /**
     * NotificationService constructor.
     *
     * @param string $subject
     * @param string $message
     * @param string $name
     * @param int $course_id
     * @param int $user_id
     * @param string $lang
     * @param int $type
     */
    public function __construct(string $subject, string $message, string $name, int $course_id, int $user_id, string $lang, int $type = 0)
    {
        $this->message      = $message;
        $this->name         = $name;
        $this->course_id    = $course_id;
        $this->user_id      = $user_id;
        $this->type         = $type;
        $this->lang         = $lang;
        $this->subject      = $subject;
    }

    /**
     * Отправка уведомлений
     *
     * @return void
     */
    public function notify(): void
    {
        NotificationsHelper::createNotification($this->name, $this->course_id, $this->user_id, $this->type, ['course_reject_message' => $this->message]);

        $this->sendEmail();
    }

    /**
     * Отправка на почту
     */
    private function sendEmail()
    {
        $data = [
            'item'          => Course::find($this->course_id),
            'lang'          => $this->lang,
            'message_text'  => $$this->message,
        ];

        $user = User::find($this->user_id);

        try {
            Mail::send('app.pages.page.emails.course_reject', ['data' => $data], function ($message) use ($user) {
                $message->from(env("MAIL_USERNAME"), env('APP_NAME'));
                $message->to($user->email, 'Receiver')->subject($this->subject);
            });

        } catch (\Exception $e) {

        }
    }
}
