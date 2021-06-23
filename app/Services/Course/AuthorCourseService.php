<?php

namespace Services\Course;

use App\Models\Contract;
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
     * @param int $contract_id
     */
    public function rejectContract(int $contract_id)
    {
        $contract = Contract::whereHas('course', function ($q) {
            return $q->whereAuthorId(Auth::user()->id);
        })->findOrFail($contract_id);

        $contract->course->update([
            'contract_status'   => 0,
            'status'            => 0
        ]);

        $this->authorContractService->rejectContract($contract->id);
    }
}
