<?php

namespace Services\Contracts;


use App\Models\AVR;
use App\Models\Contract;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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
     * AuthorAVRService constructor.
     *
     * @param AVRFilterService $AVRFilterService
     */
    public function __construct(AVRFilterService $AVRFilterService)
    {
        $this->AVRFilterService = $AVRFilterService;
    }

    /**
     * Получаем договора автора
     *
     * @param array|null $request
     * @return LengthAwarePaginator
     */
    public function getOrSearchMyContracts(array $request = null): LengthAwarePaginator
    {
        $avr = AVR::signed()->with('course');

        $avr = $this->AVRFilterService->search($avr, $request);

        return $avr->whereHas('course', function ($q) {
            return $q->whereAuthorId(Auth::user()->id);
        })->latest()->paginate(10);
    }
}
