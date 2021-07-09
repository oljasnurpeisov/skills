<?php

namespace App\Observers;

use App\Models\Contract;
use Services\Contracts\ContractLogService;

/**
 * Class ContractObserver
 *
 * @author kgurovoy@gmail.com
 * @package App\Observers
 */
class ContractObserver
{
    /**
     * @var ContractLogService
     */
    private $contractLogService;

    /**
     * ContractObserver constructor.
     *
     * @param ContractLogService $contractLogService
     */
    public function __construct(ContractLogService $contractLogService)
    {
        $this->contractLogService = $contractLogService;
    }

    /**
     * Listen to the Course updating event.
     *
     * @param Contract $contract
     * @return void
     */
    public function updating(Contract $contract)
    {
        $oldData = Contract::find($contract->id);

        if (empty($oldData) or ($oldData->status !== $contract->status) or ($oldData->route_id !== $contract->route_id)) {

            $comment = $contract->getStatusName();

            if (!empty($contract->reject_comment)) {
                $comment = $comment .'. Причина: '. $contract->reject_comment;
            }

            $this->contractLogService->create($contract->course->id, $contract->id, $contract->status, $comment);
        }
    }
}
