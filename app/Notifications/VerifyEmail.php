<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class VerifyEmail extends VerifyEmailBase
{
//    use Queueable;

    // change as you want
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }
        return (new MailMessage)
            ->greeting(__('auth.pages.greeting'))
            ->from('info@enbek.kz')
            ->subject(__('auth.pages.verification_title'))
            ->line(__('auth.pages.verification_description'))
            ->action(
                __('auth.pages.verification_title'),
                $this->verificationUrl($notifiable)
            )
            ->line(__('auth.pages.ignore_message'));
    }
}