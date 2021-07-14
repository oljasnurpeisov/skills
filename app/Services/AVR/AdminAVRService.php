<?php

namespace Services\AVR;

use App\Models\AVR;
use App\Services\Signing\DocumentService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Services\Contracts\AVRServiceRouting;

class AdminAVRService
{
    /**
     * @var AVRServiceRouting
     */
    private $AVRServiceRouting;

    /**
     * @var DocumentService
     */
    private $documentService;

    /**
     * @var AVRLogService
     */
    private $AVRLogService;

    /**
     * AdminAVRService constructor.
     *
     * @param AVRServiceRouting $AVRServiceRouting
     * @param DocumentService $documentService
     * @param AVRLogService $AVRLogService
     */
    public function __construct(AVRServiceRouting $AVRServiceRouting, DocumentService $documentService, AVRLogService $AVRLogService)
    {
        $this->AVRServiceRouting    = $AVRServiceRouting;
        $this->documentService      = $documentService;
        $this->AVRLogService        = $AVRLogService;
    }

    /**
     * Accept signed act
     *
     * @param int $avr_id
     * @param string|null $message
     * @param array $validationResponse
     * @return void
     */
    public function acceptAvr(int $avr_id, string $message = null, array $validationResponse = [])
    {
        $avr = AVR::whereHas('current_route', function ($q) {
            return $q->whereRoleId(Auth::user()->role->role_id);
        })->findOrFail($avr_id);

        if ($avr->document && $message) {
            $this->documentService->attachSignature($avr->document, $message, $validationResponse);
        }

        $this->AVRServiceRouting->toNextRoute($avr);
    }

    /**
     * Получение истории
     *
     * @param int $contract_id
     * @return Collection
     */
    public function getHistory(int $contract_id): Collection
    {
        $contract = AVR::findOrFail($contract_id);

        return $this->AVRLogService->getLogs($contract->course->id);
    }
}
