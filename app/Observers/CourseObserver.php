<?php

namespace App\Observers;

use App\Models\Course;
use Carbon\Carbon;

class CourseObserver
{
    /**
     * @var Course
     */
    private $course;

    /**
     * CourseObserver constructor.
     *
     * @param Course $course
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * Listen to the Course updating event.
     *
     * @param Course $course
     * @return void
     */
    public function updating(Course $course)
    {
        $oldData = $this->course->find($course->id);

        // Set publish at
        if ($course->status === 3 and $oldData->status !== 3) {
            $course->publish_at = Carbon::now();
        }
    }
}
