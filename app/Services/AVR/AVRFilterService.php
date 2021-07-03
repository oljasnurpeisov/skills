<?php

namespace Services\AVR;

use App\Models\AVR;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Services\Contracts\ContractFilterService;

class AVRFilterService
{
    /**
     * @var AVR
     */
    private $AVR;

    /**
     * @var ContractFilterService
     */
    private $contractFilterService;

    /**
     * AVRFilterService constructor.
     *
     * @param AVR $AVR
     * @param ContractFilterService $contractFilterService
     */
    public function __construct(AVR $AVR, ContractFilterService $contractFilterService)
    {
        $this->AVR                      = $AVR;
        $this->contractFilterService    = $contractFilterService;
    }

    /**
     * Получение АВР
     *
     * @TODO REMOVE LIKE!
     *
     * @param array $request
     * @param string|null $scope
     * @return LengthAwarePaginator
     */
    public function getOrSearch(array $request, string $scope = null): LengthAwarePaginator
    {
        $avr = $this->AVR->with(['course', 'course.user']);

        if (!empty($scope)) {
            $avr = $avr->$scope();
        }

        $avr = $this->search($avr, $request);

        return $avr->latest()->paginate(10);
    }

    /**
     * Поиск/фильтрация
     *
     * @param Builder $avr
     * @param array $request
     * @return Builder
     */
    public function search(Builder $avr, array $request): Builder
    {
        $avr = $this->contractFilterService->searchByCompanyName($avr, $request);
        $avr = $this->contractFilterService->searchByCourseName($avr, $request);
        $avr = $this->searchByAVRName($avr, $request);
        $avr = $this->searchByContractNumber($avr, $request);
        $avr = $this->searchByAVRSum($avr, $request);
        $avr = $this->filterByAVRStatus($avr, $request);
        $avr = $this->searchByPeriod($avr, $request);

        return $avr;
    }

    /**
     * Поиск по номеру договора
     *
     * @TODO REMOVE LIKE!
     *
     * @param Builder $avr
     * @param array $request
     * @return Builder
     */
    public function searchByContractNumber(Builder $avr, array $request): Builder
    {
        if (!empty($request['contract_number'])) {
            $avr =  $avr->whereHas('contract', function($q) use ($request) {
                return $q->where('number', 'like', '%'. $request['contract_number'] .'%');
            });
        }

        return $avr;
    }

    /**
     * Поиск по номеру АВР
     *
     * @TODO REMOVE LIKE!
     *
     * @param Builder $avr
     * @param array $request
     * @return Builder
     */
    public function searchByAVRName(Builder $avr, array $request): Builder
    {
        if (!empty($request['avr_number'])) {
            $avr =  $avr->where('number', 'like', '%'. $request['avr_number'] .'%');
        }

        return $avr;
    }

    /**
     * Поиск по сумме
     *
     * @TODO REMOVE LIKE!
     *
     * @param Builder $avr
     * @param array $request
     * @return Builder
     */
    public function searchByAVRSum(Builder $avr, array $request): Builder
    {
        if (!empty($request['sum'])) {
            $avr =  $avr->where('sum', 'like', '%'. (int) $request['sum'] .'%');
        }

        return $avr;
    }

    /**
     * Фильтр по статусу
     *
     * @param Builder $avr
     * @param array $request
     * @return Builder
     */
    public function filterByAVRStatus(Builder $avr, array $request): Builder
    {
        if (!empty($request['avr_status'])) {
            return $avr->whereStatus($request['avr_status']);
        }

        return $avr;
    }

    /**
     * Поиск по периоду
     *
     * @param Builder $avr
     * @param array $request
     * @return Builder
     */
    public function searchByPeriod(Builder $avr, array $request): Builder
    {
        if (!empty($request['avr_period'])) {
            $dates      = explode(',', $request['avr_period']);
            $start_at   = Carbon::parse($dates[0]);
            $end_at     = Carbon::parse($dates[1]);

            return $avr->where(function($q) use ($start_at, $end_at) {
                // type 1
                $q->where(function ($b) use ($start_at, $end_at) {
                    $b->whereDate('start_at', '>=', $start_at)
                        ->whereDate('start_at', '<=', $end_at);
                });

                //type 2
                $q->orWhere(function ($d) use ($start_at, $end_at) {
                    $d->whereDate('start_at', '<=', $start_at)
                        ->whereDate('end_at', '>=', $end_at);
                });

                //type 3
                $q->orWhere(function ($c) use ($start_at, $end_at) {
                    $c->whereDate('end_at', '>=', $start_at)
                        ->whereDate('end_at', '<=', $end_at);
                });

                // type 4
                $q->orWhere(function ($e) use ($start_at, $end_at) {
                    $e->whereDate('start_at', '>=', $start_at)
                        ->whereDate('end_at', '<=', $end_at);
                });

                return $q;
            });
        }

        return $avr;
    }
}
