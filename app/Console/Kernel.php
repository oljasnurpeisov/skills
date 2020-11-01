<?php

namespace App\Console;

use App\Console\Commands\ProfessionSkillRelation;
use App\Console\Commands\ProfessionsUpdate;
use App\Console\Commands\SkillsUpdate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\RemoveUnconfirmedEmailUsers;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        RemoveUnconfirmedEmailUsers::class,
        SkillsUpdate::class,
        ProfessionsUpdate::class,
        ProfessionSkillRelation::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('remove:user')
            ->everySixHours();

        $schedule->command('update:skills')
            ->daily();

        $schedule->command('update:professions')
            ->daily();

        $schedule->command('update:profession_skill_relation')
            ->daily();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
