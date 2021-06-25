<?php

namespace Services\Course;

use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Services\Contracts\AuthorContractService;
use Services\Contracts\ContractServiceRouting;

class AuthorCourseService
{
    /**
     * @var AuthorContractService
     */
    private $authorContractService;

    /**
     * @var ContractServiceRouting
     */
    private $contractServiceRouting;

    /**
     * ContractService constructor.
     *
     * @param AuthorContractService $authorContractService
     * @param ContractServiceRouting $contractServiceRouting
     */
    public function __construct(AuthorContractService $authorContractService, ContractServiceRouting $contractServiceRouting)
    {
        $this->authorContractService    = $authorContractService;
        $this->contractServiceRouting   = $contractServiceRouting;
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

        $this->authorContractService->rejectContract($contract);
    }

    /**
     * Заглушка, пока нет ЭЦП
     *
     * @TODO: REMOVE THIS!!!
     *
     * @param int $contract_id
     */
    public function acceptContract(int $contract_id)
    {
        $contract = Contract::whereHas('course', function ($q) {
            return $q->whereAuthorId(Auth::user()->id);
        })->findOrFail($contract_id);

        // опубликовать курс, если платный или бесплатный
        $contract->course()->update([
            'status' => 3,
            'publish_at' => Carbon::now()
        ]);

        $this->contractServiceRouting->toNextRoute($contract);
    }
}
