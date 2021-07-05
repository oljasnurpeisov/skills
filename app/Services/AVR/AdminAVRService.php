<?php

namespace Services\AVR;

use App\Models\AVR;
use Illuminate\Support\Facades\Auth;
use Services\Contracts\AVRServiceRouting;

class AdminAVRService
{
    /**
     * @var AVRServiceRouting
     */
    private $AVRServiceRouting;

    /**
     * AdminAVRService constructor.
     *
     * @param AVRServiceRouting $AVRServiceRouting
     */
    public function __construct(AVRServiceRouting $AVRServiceRouting)
    {
        $this->AVRServiceRouting = $AVRServiceRouting;
    }

    /**
     * Заглушка, пока нет ЭЦП
     *
     * @TODO: REMOVE THIS!!!
     *
     * @param int $avr_id
     */
    public function acceptAvr(int $avr_id)
    {
        $avr = AVR::whereHas('current_route', function ($q) {
            return $q->whereRoleId(Auth::user()->role->role_id);
        })->findOrFail($avr_id);

        $this->AVRServiceRouting->toNextRoute($avr);
    }
}
