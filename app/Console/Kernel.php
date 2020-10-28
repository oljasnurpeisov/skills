<?php

namespace App\Console;

use App\Models\Skill;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Orchestra\Parser\Xml\Facade as XmlParser;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            User::where('created_at', '<=', Carbon::now()->subHours(72)->toDateTimeString())->where('email_verified_at', '=', null)->delete();
        })->everySixHours();

        $schedule->call($this->skillsDataUpdate())->daily();
    }

    public function skillsDataUpdate(){
        $xml = XmlParser::load(url('https://iac2:Iac2007RBD@www.enbek.kz/feed/resume/cl_hard_skills.xml'));

        $skills = $xml->parse([
            'data' => ['uses' => 'row[field(::name=@)]'],
        ]);

        foreach (array_values($skills)[0] as $skill){

            $user = Skill::updateOrCreate([
                'code_skill' => $skill['codskill']
            ], [
                'fl_check' => $skill['fl_check'],
                'name_ru' => $skill['name_skill'],
                'name_kk'=> $skill['name_skill_kz'],
                'fl_show'=> $skill['fl_show'],
                'uid'=> $skill['uid'],
            ]);
        }
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
