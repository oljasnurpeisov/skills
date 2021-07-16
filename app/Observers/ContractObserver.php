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

//        if () {
        if (!empty($oldData->route_id) && !empty($contract->route_id) && !empty($contract->status) && !empty($contract->type) and ($oldData->route_id !== $contract->route_id || $contract->status !== $oldData->status)) {

//            if ($oldData->status === 1) {
//                $comment = $contract->getStatusNameForLog($oldData->status, $oldData->route_id);
//            } else {
//                $comment = $contract->getStatusNameForLog($contract->status, $oldData->route_id);
//            }

            if ($contract->status !== 1) {
                $comment = $contract->getStatusNameForLog($contract->status, $oldData->route_id);
            } else {
                $comment = $contract->getStatusNameForLog($oldData->status, $oldData->route_id);
            }

            if (!empty($contract->reject_comment)) {
                $comment = $comment .". <br> Причина: ". $contract->reject_comment;
            }

            $this->contractLogService->create($contract->course->id, $contract->id, $contract->status, $comment);
        }
    }
}
