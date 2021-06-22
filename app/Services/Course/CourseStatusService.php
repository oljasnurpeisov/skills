<?php

namespace Services\Course;

use App\Models\Course;
use Services\Contracts\ContractService;

/**
 * Class CourseStatusService
 *
 * @author kgurovoy@gmail.com
 * @package Services\Course
 */
class CourseStatusService
{
    /**
     * @var ContractService
     */
    private $contractService;

    /**
     * CourseStatusService constructor.
     *
     * @param ContractService $contractService
     */
    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    /**
     * Одобрение курса
     *
     * @param int $course_id
     */
    public function acceptCourse(int $course_id): void
    {
        Course::find($course_id)->update([
            'status' => 5
        ]);
    }
}
