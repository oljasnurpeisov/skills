<?php

namespace Services\Contracts;

use App\Models\Contract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ContractFilterService
 *
 * @author kgurovoy@gmail.com
 * @package Services\Contracts
 */
class ContractFilterService
{
    /**
     * @var Contract
     */
    private $contract;

    /**
     * ContractService constructor.
     *
     * @param Contract $contract
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Получение договоров
     *
     * @TODO REMOVE LIKE!
     *
     * @param array $request
     * @param string|null $scope
     * @return LengthAwarePaginator
     */
    public function getOrSearch(array $request, string $scope = null): LengthAwarePaginator
    {
        $contracts = $this->contract->with(['course', 'course.user']);

        if (!empty($scope)) {
            $contracts = $contracts->$scope();
        }

        $contracts = $this->search($contracts, $request);

        return $contracts->latest()->paginate(10);
    }

    /**
     * Поиск/фильтрация
     *
     * @param Builder $contracts
     * @param array $request
     * @return Builder
     */
    public function search(Builder $contracts, array $request): Builder
    {
        $contracts = $this->searchByContractName($contracts, $request);
        $contracts = $this->searchByCourseName($contracts, $request);
        $contracts = $this->searchByCompanyName($contracts, $request);
        $contracts = $this->filterByContractStatus($contracts, $request);
        $contracts = $this->filterByContractType($contracts, $request);
        $contracts = $this->filterByCourseType($contracts, $request);
        $contracts = $this->filterByQuota($contracts, $request);

        return $contracts;
    }

    /**
     * Поиск по номеру договора
     *
     * @TODO REMOVE LIKE!
     *
     * @param Builder $contracts
     * @param array $request
     * @return Builder
     */
    private function searchByContractName(Builder $contracts, array $request): Builder
    {
        if (!empty($request['contract_number'])) {
            $contracts =  $contracts->where('number', 'like', '%'. $request['contract_number'] .'%');
        }

        return $contracts;
    }

    /**
     * Поиск по названию курса
     *
     * @TODO REMOVE LIKE!
     *
     * @param Builder $contracts
     * @param $request
     * @return Builder
     */
    private function searchByCourseName(Builder $contracts, array $request): Builder
    {
        if (!empty($request['course_name'])) {
            $contracts =  $contracts->whereHas('course', function($q) use ($request) {
                return $q->where('name', 'like', '%'. $request['course_name'] .'%');
            });
        }

        return $contracts;
    }

    /**
     * Поиск по компании автора
     *
     * @TODO REMOVE LIKE!
     *
     * @param Builder $contracts
     * @param $request
     * @return Builder
     */
    private function searchByCompanyName(Builder $contracts, array $request): Builder
    {
        if (!empty($request['company_name'])) {
            $contracts =  $contracts->whereHas('course.user', function($q) use ($request) {
                return $q->where('company_name', 'like', '%'. $request['company_name'] .'%');
            });
        }

        return $contracts;
    }

    /**
     * Фильтр по статусам договора
     *
     * @param Builder $contracts
     * @param $request
     * @return Builder
     */
    private function filterByContractStatus(Builder $contracts, array $request): Builder
    {
        if (!empty($request['contract_status'])) {
            return $contracts->whereStatus($request['contract_status']);
        }

        return $contracts;
    }

    /**
     * Фильтр по типу договора
     *
     * @param Builder $contracts
     * @param $request
     * @return Builder
     */
    private function filterByContractType(Builder $contracts, array $request): Builder
    {
        if (!empty($request['contract_type'])) {
            return $contracts->whereType($request['contract_type']);
        }

        return $contracts;
    }

    /**
     * Фильтр по типу курса
     *
     * @param Builder $contracts
     * @param $request
     * @return Builder
     */
    private function filterByCourseType(Builder $contracts, array $request): Builder
    {
        if (!empty($request['course_type'])) {
            if ($request['course_type'] == 1) {
                $contracts =  $contracts->whereHas('course', function($q) {
                    return $q->free();
                });
            }

            if ($request['course_type'] == 2) {
                $contracts = $contracts->whereHas('course', function($q) {
                    return $q->paid();
                });
            }

            if ($request['course_type'] == 3) {
                $contracts = $contracts->whereHas('course', function($q) {
                    return $q->quota();
                });
            }
        }

        return $contracts;
    }

    /**
     * Фильтр по доступности квоты
     *
     * @param Builder $contracts
     * @param $request
     * @return Builder
     */
    private function filterByQuota(Builder $contracts, array $request): Builder
    {
        if (!empty($request['contract_quota'])) {
            if ($request['contract_quota'] === 1) {
                $contracts = $contracts->whereType(3);
            }

            if ($request['contract_quota'] === 0) {
                $contracts = $contracts->where('type', '!=', 3);
            }
        }

        return $contracts;
    }
}
