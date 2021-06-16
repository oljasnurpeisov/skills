<?php

namespace Services\Course;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Services\Contracts\AuthorContractService;

class AuthorCourseService
{
    /**
     * @var AuthorContractService
     */
    private $authorContractService;

    /**
     * ContractService constructor.
     *
     * @param AuthorContractService $authorContractService
     */
    public function __construct(AuthorContractService $authorContractService)
    {
        $this->authorContractService = $authorContractService;
    }

    /**
     * Договор курса отклонен автором
     *
     * @param int $id
     */
    public function rejectContract(int $id)
    {
        $course = Course::whereAuthorId(Auth::user()->id)->findOrFail($id);

        $course->update([
            'contract_status'   => 0,
            'status'            => 0
        ]);

        $this->authorContractService->rejectContract($course->id);
    }
}
