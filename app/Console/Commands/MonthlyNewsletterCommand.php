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
    protected $signature = 'newsletter-monthly';

    protected $description = 'newsletter-monthly on email';

    public function handle()
    {
        $users = User::whereNotNull('email')->get();
        $carbon = now()->subMonth();
        $courses = Course::whereMonth('publish_at', $carbon->month)
            ->whereYear('publish_at', $carbon->year)
            ->get();

        foreach ($users as $user) {
            try {
                Mail::to($user)
                    ->queue(new MonthlyNewsletterMail($courses));
            } catch (Swift_RfcComplianceException $ex) {

            }

        }
    }
}
