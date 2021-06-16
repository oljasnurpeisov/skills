<?php

namespace Services\Contracts;


use App\Models\Contract;

class AuthorContractService
{
    /**
     * Договор курса отклонен автором
     *
     * @param int $course_id
     * @return void
     */
    public function rejectContract(int $course_id): void
    {
        Contract::whereCourseId($course_id)->latest()->first()->update([
            'status' => 4
        ]);
    }
}
