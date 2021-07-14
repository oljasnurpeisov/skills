<?php

namespace App\Observers;

use App\Models\AVR;
use App\Models\Contract;
use Services\AVR\AVRLogService;

/**
 * Class ContractObserver
 *
 * @author kgurovoy@gmail.com
 * @package App\Observers
 */
class AVRObserver
{
    /**
     * @var AVRLogService
     */
    private $AVRLogService;

    /**
     * ContractObserver constructor.
     *
     * @param AVRLogService $AVRLogService
     */
    public function __construct(AVRLogService $AVRLogService)
    {
        $this->AVRLogService = $AVRLogService;
    }

    /**
     * Listen to the Course updating event.
     *
     * @param AVR $avr
     * @return void
     */
    public function updating(AVR $avr)
    {
        $oldData = AVR::find($avr->id);

//        if () {
        if (!empty($oldData->route_id) && !empty($avr->route_id) && !empty($avr->status) && ($oldData->route_id !== $avr->route_id || $avr->status !== $oldData->status)) {

            if ($oldData->status === 1) {
                $comment = $avr->getStatusNameForLog($oldData->status, $oldData->route_id);
            } else {
                $comment = $avr->getStatusNameForLog($avr->status, $oldData->route_id);
            }

            if (!empty($avr->reject_comment)) {
                $comment = $comment .". <br> Причина: ". $avr->reject_comment;
            }

            $this->AVRLogService->create($avr->course->id, $avr->id, $avr->status, $comment);
        }
    }
}
