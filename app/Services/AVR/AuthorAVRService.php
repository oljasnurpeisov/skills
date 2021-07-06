<?php

namespace Services\Contracts;


use App\Models\AVR;
use App\Models\Course;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Libraries\Word\AVRGen;
use Services\AVR\AVRFilterService;

/**
 * Class AuthorAVRService
 *
 * @author kgurovoy@gmail.com
 * @package Services\Contracts
 */
class AuthorAVRService
{
    /**
     * @var AVRFilterService
     */
    private $AVRFilterService;

    /**
     * @var AVRServiceRouting
     */
    private $AVRServiceRouting;

    /**
     * AuthorAVRService constructor.
     *
     * @param AVRFilterService $AVRFilterService
     * @param AVRServiceRouting $AVRServiceRouting
     */
    public function __construct(AVRFilterService $AVRFilterService, AVRServiceRouting $AVRServiceRouting)
    {
        $this->AVRFilterService     = $AVRFilterService;
        $this->AVRServiceRouting    = $AVRServiceRouting;
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
        $avr = AVR::whereHas('course', function ($q) {
            return $q->whereAuthorId(Auth::user()->id);
        })->whereHas('current_route', function ($q) {
            return $q->whereRoleId(Auth::user()->role->role_id);
        })->findOrFail($avr_id);

        $this->AVRServiceRouting->toNextRoute($avr);
    }

    /**
     * Обновление номера АВР и счета фактуры
     *
     * @param int $avr_id
     * @param array $request
     */
    public function updateAVR(int $avr_id, array $request)
    {
        $avr = AVR::findOrFail($avr_id);
        $avr->update(['invoice_link' => $request['invoice']]);

        $avrGen = new AVRGen();
        $avrGen->addAVRNumber($avr, $request['avr_number']);
    }

    /**
     * Получаем договора автора
     *
     * @param array|null $request
     * @return LengthAwarePaginator
     */
    public function getOrSearchMyContracts(array $request = null): LengthAwarePaginator
    {
        $avr = AVR::with('course');

        $avr = $this->AVRFilterService->search($avr, $request);

        return $avr->whereHas('course', function ($q) {
            return $q->whereAuthorId(Auth::user()->id);
        })->latest()->paginate(10);
    }
}
