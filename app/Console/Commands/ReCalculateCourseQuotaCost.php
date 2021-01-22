<?php

namespace App\Console\Commands;

use App\Extensions\CalculateQuotaCost;
use App\Extensions\NotificationsHelper;
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
        $courses = CourseQuotaCost::where('created_at', '<=', Carbon::now()->subMonths(3)->toDateTimeString())
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('course_id');

        foreach ($courses as $course) {
            $calculate_quota_cost = CalculateQuotaCost::calculate_quota_cost($course, true);
            $qouta_cost_item = new CourseQuotaCost;
            $qouta_cost_item->course_id = $course->id;
            $qouta_cost_item->cost = $calculate_quota_cost;
            $qouta_cost_item->save();

            // Если курс доступен по квоте, уведомить автора
            if ($course->quota_status == 2) {
                $notification_data = [
                    'course_quota_cost' => $calculate_quota_cost
                ];
                $notification_name = "notifications.re_calculate_quota_cost_message";
                NotificationsHelper::createNotification($notification_name, $course->id, $course->user->id, 0, $notification_data);
            }
        }

        return '';
    }
}
