<?php

namespace App\Console\Commands\AVR;

use App\Models\Course;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Libraries\Word\AVRGen;
use PhpOffice\PhpWord\Exception\Exception;
use Services\Contracts\AVRServiceRouting;

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
     * @var AVRServiceRouting
     */
    private $AVRServiceRouting;
    /**
     * @var Route
     */
    private $route;

    /**
     * Create a new command instance.
     *
     * @param AVRServiceRouting $AVRServiceRouting
     * @param Route $route
     */
    public function __construct(AVRServiceRouting $AVRServiceRouting, Route $route)
    {
        parent::__construct();
        $this->AVRServiceRouting = $AVRServiceRouting;
        $this->route = $route;
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
            $newAvr = $avr->generate($start_at, $end_at);

            $this->AVRServiceRouting->toNextRoute($newAvr);
        }
    }
}
