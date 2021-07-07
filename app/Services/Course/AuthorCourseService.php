<?php

namespace Services\Course;

use App\Models\Contract;
use App\Services\Signing\DocumentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Services\Contracts\AuthorContractService;
use Services\Contracts\ContractServiceRouting;
use Spatie\ArrayToXml\ArrayToXml;

class AuthorCourseService
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
     * @var DocumentService
     */
    private $documentService;

    /**
     * ContractService constructor.
     *
     * @param AuthorContractService $authorContractService
     * @param ContractServiceRouting $contractServiceRouting
     */
    public function __construct(
        AuthorContractService $authorContractService,
        ContractServiceRouting $contractServiceRouting,
        DocumentService $documentService
    )
    {
        $this->authorContractService    = $authorContractService;
        $this->contractServiceRouting   = $contractServiceRouting;
        $this->documentService          = $documentService;
    }

    /**
     * Договор курса отклонен автором
     *
     * @param int $contract_id
     */
    public function rejectContract(int $contract_id)
    {
        $contract = Contract::whereHas('course', function ($q) {
            return $q->whereAuthorId(Auth::user()->id);
        })->findOrFail($contract_id);

        $this->authorContractService->rejectContract($contract);
    }

    /**
     * Заглушка, пока нет ЭЦП
     *
     * @TODO: REMOVE THIS!!!
     *
     * @param int $contract_id
     */
    public function generateXml(int $contract_id)
    {
        $contract = Contract::whereHas('course', function ($q) {
            return $q->whereAuthorId(Auth::user()->id);
        })->findOrFail($contract_id);

        return $this->authorContractService->generateXml($contract);
    }

    /**
     * Attache signature info and send to next route
     * @param int $contract_id
     * @param string|null $message
     * @param array $validationResponse
     */
    public function acceptContract(int $contract_id, string $message = null, array $validationResponse = [])
    {
        /** @var Contract $contract */
        $contract = Contract::whereHas('course', function ($q) {
            return $q->whereAuthorId(Auth::user()->id);
        })->findOrFail($contract_id);

        // опубликовать курс, если платный или бесплатный

        if (!$contract->isQuota()) {
            $contract->course()->update([
                'status' => 3,
                'publish_at' => Carbon::now()
            ]);
        }

        // либо прикрепить информацию о подписании

        else {
            if ($contract->document && $message) {
                $this->documentService->attachSignature($contract->document, $message, $validationResponse);
            }
        }

        $this->contractServiceRouting->toNextRoute($contract);
    }
}
