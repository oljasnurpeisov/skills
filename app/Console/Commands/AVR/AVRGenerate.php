<?php

namespace App\Console\Commands\AVR;

use App\Models\Course;
use Illuminate\Console\Command;
use Libraries\Word\AVRGen;

class AVRGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generateAVR';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерация актов выполненых работ за предыдущий месяц';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $courses = Course::quota()->get();

        foreach($courses as $course) {
            $avr = new AVRGen($course);
            $avr->generate();
        }
    }
}
