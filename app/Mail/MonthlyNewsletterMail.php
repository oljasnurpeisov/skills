<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class MonthlyNewsletterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Collection
     */
    private $courses;

    public function __construct(Collection $courses)
    {
        $this->courses = $courses;
    }

    public function build()
    {
        return $this->view('emails.monthly-newsletter', [
            'courses' => $this->courses,
            'count'   => $this->courses->count(),
        ])
            ->subject('Новые курсы');
    }
}
