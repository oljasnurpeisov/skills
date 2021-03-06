<?php

namespace Services\Course;

use App\Models\Contract;
use App\Models\Course;
use App\Services\Signing\DocumentService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Services\Contracts\AuthorContractService;
use Services\Contracts\ContractLogService;
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
     * @var DocumentService
     */
    private $documentService;
    /**
     * @var ContractLogService
     */
    private $contractLogService;

    /**
     * ContractService constructor.
     *
     * @param AuthorContractService $authorContractService
     * @param ContractServiceRouting $contractServiceRouting
     * @param ContractService $contractService
     * @param DocumentService $documentService
     * @param ContractLogService $contractLogService
     */
    public function __construct(
        AuthorContractService $authorContractService,
        ContractServiceRouting $contractServiceRouting,
        ContractService $contractService,
        DocumentService $documentService,
        ContractLogService $contractLogService
    )
    {
        $this->authorContractService    = $authorContractService;
        $this->contractServiceRouting   = $contractServiceRouting;
        $this->contractService          = $contractService;
        $this->documentService          = $documentService;
        $this->contractLogService       = $contractLogService;
    }

    /**
     * Accept signed contract
     *
     * @param int $contract_id
     * @param string|null $message
     * @param array $validationResponse
     * @return void
     */
    public function acceptContract(int $contract_id, string $message = null, array $validationResponse = []): void
    {
        $contract = $this->contractService->getContractIfMyCurrentRoute($contract_id);

        if ($contract->document && $message) {
            $this->documentService->attachSignature($contract->document, $message, $validationResponse);
        }

        $this->contractServiceRouting->toNextRoute($contract);
    }

    /**
     * ???????????????? ???????????? ???? ??????????
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
     * ?????????????????? ????????
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
     * ?????????????????? ???????? ?????? ?????????? ?????? ???????????????????? ????????????????
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

    /**
     * ?????????????????? ??????????????
     *
     * @param int $contract_id
     * @return Collection
     */
    public function getHistory(int $contract_id): Collection
    {
        $contract = Contract::findOrFail($contract_id);

        return $this->contractLogService->getLogs($contract->type, $contract->course->id);
    }
}
