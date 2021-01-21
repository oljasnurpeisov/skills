<?php

namespace App\Console\Commands;

use App\Extensions\CalculateQuotaCost;
use App\Http\Controllers\Admin\CourseController;
use App\Models\Course;
use App\Models\CourseQuotaCost;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReCalculateCourseQuotaCost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:course_quota_cost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update quota course cost';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $courses = CourseQuotaCost::where('created_at', '<=', Carbon::now()->subMonths(3)->toDateTimeString())->get();

        foreach ($courses as $course) {
            $calculate_quota_cost = CalculateQuotaCost::calculate_quota_cost($course, true);
            $qouta_cost_item = new CourseQuotaCost;
            $qouta_cost_item->course_id = $course->id;
            $qouta_cost_item->cost = $calculate_quota_cost;
            $qouta_cost_item->save();
        }

        return '';
    }
}
