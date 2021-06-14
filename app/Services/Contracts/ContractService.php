<?php

namespace Services\Contracts;

use App\Models\Contract;
use App\Models\Course;
use Doctrine\DBAL\Query;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class ContractService
 * @package Services\Contracts
 */
class ContractService
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
     * @param string $keywords
     * @return LengthAwarePaginator
     */
    public function getOrSearch(string $keywords=null): LengthAwarePaginator
    {
        $courses = $this->contract;

        if (!empty($keywords)) {
            $courses = $courses->whereHas('course', function($q) use ($keywords) {
                return $q->where('name', 'like', '%'. $keywords .'%');
            });
        }

        return $courses->with(['course', 'course.user'])->latest()->paginate(10);
    }
}
