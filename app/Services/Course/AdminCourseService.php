<?php

namespace Services\Course;

use App\Models\Contract;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Services\Contracts\AuthorContractService;
use Services\Contracts\ContractService;
use Services\Contracts\ContractServiceRouting;

class AdminCourseService
{
    /**
     * @var AuthorContractService
     */
    private $authorContractService;

    /**
     * @var ContractServiceRouting
     */
    private $contractServiceRouting;
    /**
     * @var ContractService
     */
    private $contractService;

    /**
     * ContractService constructor.
     *
     * @param AuthorContractService $authorContractService
     * @param ContractServiceRouting $contractServiceRouting
     * @param ContractService $contractService
     */
    public function __construct(AuthorContractService $authorContractService, ContractServiceRouting $contractServiceRouting,
                                ContractService $contractService)
    {
        $this->authorContractService    = $authorContractService;
        $this->contractServiceRouting   = $contractServiceRouting;
        $this->contractService          = $contractService;
    }

    /**
     * Заглушка, пока нет ЭЦП
     *
     * @TODO: REMOVE THIS!!!
     *
     * @param int $contract_id
     * @return void
     */
    public function acceptContract(int $contract_id): void
    {
        $contract = $this->contractService->getContractIfMyCurrentRoute($contract_id);

        $this->contractServiceRouting->toNextRoute($contract);
    }

    /**
     * Отменяем доступ по квоте
     *
     * @param int $course_id
     * @return void
     */
    public function rejectQuota(int $course_id): void
    {
        Course::find($course_id)->update([
            'quota_status' => 0
        ]);
    }

    /**
     * Отклоняем курс
     *
     * @param int $course_id
     * @return void
     */
    public function rejectCourse(int $course_id)
    {
        Course::find($course_id)->update([
            'status' => 2
        ]);
    }

    /**
     * Отклоняем курс или квоту при отклонении договора
     *
     * @param Contract $contract
     */
    public function rejectOnContract(Contract $contract)
    {
        if ($contract->isPaid() or $contract->isFree()) {
            $this->rejectCourse($contract->course->id);

            if ($contract->isPaid()) {
                $this->rejectQuota($contract->course->id);
            }
        } else {
            $this->rejectQuota($contract->course->id);
        }
    }
}
