<?php

namespace App\Console\Commands;

use App\Mail\MonthlyNewsletterMail;
use App\Models\Course;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Swift_RfcComplianceException;

class MonthlyNewsletterCommand extends Command
{
    protected $signature = 'newsletter-monthly {email?}';

    protected $description = 'newsletter-monthly on email';

    public function handle()
    {

        $carbon = now()->subMonth();
        $courses = Course::whereMonth('publish_at', $carbon->month)
            ->whereYear('publish_at', $carbon->year)
            ->get();

        if ($this->argument('email')) {
            Mail::to($this->argument('email'))
                ->queue(new MonthlyNewsletterMail($courses));
            return;
        }

        $users = User::whereNotNull('email')->get();

        foreach ($users as $user) {
            try {
                Mail::to($user)
                    ->queue(new MonthlyNewsletterMail($courses));
            } catch (Swift_RfcComplianceException $ex) {

            }

        }
    }
}
