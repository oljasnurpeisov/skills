<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;
    public $verifyUrl;
    protected $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url,$user)
    {
        $this->verifyUrl = $url;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'mymail@gmail.com';
        $name = 'Enbek.kz';
        $subject = __('auth.pages.verification_title');
        return $this->to($this->user)->subject($subject)->from($address, $name)->
        markdown('app.pages.page.emails.verify',['url' => $this->verifyUrl,'user' => $this->user]);
    }
}
