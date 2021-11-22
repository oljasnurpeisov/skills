<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class ErrorOnPageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $url;
    private $phone;
    private $text;
    private $comment;

    public function __construct($url, $phone, $text, $comment)
    {

        $this->url = $url;
        $this->phone = $phone;
        $this->text = $text;
        $this->comment = $comment;
    }

    public function build()
    {
        return $this->view('emails.error-on-page', [
            'url'     => $this->url,
            'phone'   => $this->phone,
            'text'    => $this->text,
            'comment' => $this->comment,
        ]);
    }
}
