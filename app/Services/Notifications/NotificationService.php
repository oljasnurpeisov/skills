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
    public function __construct(string $subject, string $name, int $course_id, int $user_id, string $lang, int $type = 0, string $message = null)
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
        if (!empty($this->message)) {
            $message = ['reject_message' => $this->message];
        }

        NotificationsHelper::createNotification($this->name, $this->course_id, $this->user_id, $this->type, $message ?? null);
    }
}
