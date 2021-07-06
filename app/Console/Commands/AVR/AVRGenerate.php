<?php

namespace App\Console\Commands\AVR;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Libraries\Word\AVRGen;
use PhpOffice\PhpWord\Exception\Exception;

class AVRGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:AVR';

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
     * @throws Exception
     */
    public function handle()
    {
        $start_at   = Carbon::now()->addMonths(-1)->startOfMonth();
        $end_at     = Carbon::now()->addMonths(-1)->endOfMonth();

        $courses = Course::quota()
            ->whereHas('certificate')
            ->with('certificate', 'user')
//            ->whereDate()
            ->get();

        foreach($courses as $course) {
            $avr = new AVRGen($course);
            $avr->generate($start_at, $end_at);
        }
    }
}
