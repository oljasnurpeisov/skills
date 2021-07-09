<?php

namespace Services\Contracts;

use App\Models\ContractLog;
use Illuminate\Support\Collection;

/**
 * Class ContractLogService
 *
 * @author kgurovoy@gmail.com
 * @package Services\Contracts
 */
class ContractLogService
{
    /**
     * @var ContractLog
     */
    private $contractLog;

    /**
     * ContractLogService constructor.
     *
     * @param ContractLog $contractLog
     */
    public function __construct(ContractLog $contractLog)
    {
        $this->contractLog = $contractLog;
    }

    /**
     * Добавление записи в лог
     *
     * @param int $course_id
     * @param int $contract_id
     * @param int $contract_status
     * @param string|null $comment
     * @return void
     */
    public function create(int $course_id, int $contract_id, int $contract_status, string $comment = null): void
    {
        $this->contractLog->create([
            'course_id'         => $course_id,
            'contract_id'       => $contract_id,
            'contract_status'   => $contract_status,
            'comment'           => $comment
        ]);
    }

    /**
     * Получение логов договора курса
     * с группировокой по номеру договора и типу
     *
     * @param int $contract_type
     * @param $course_id
     * @return Collection
     */
    public function getLogs(int $contract_type, $course_id): Collection
    {
        return $this->contractLog
                ->whereCourseId($course_id)
                ->whereHas('contract', function ($q) use ($contract_type) {
                    return $q->whereType($contract_type);
                })
                ->orderByDesc('created_at')
                ->orderByDesc('contract_id')
                ->get();
    }
}
