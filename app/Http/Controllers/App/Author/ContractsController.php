<?php

namespace App\Http\Controllers\App\Author;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Services\Files\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Services\Contracts\AuthorContractService;
use Services\Contracts\ContractService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContractsController extends Controller
{
    /**
     * @var AuthorContractService
     */
    private $authorContractService;

    /**
     * @var ContractService
     */
    private $contractService;

    /**
     * ContractsController constructor.
     *
     * @param AuthorContractService $authorContractService
     * @param ContractService $contractService
     */
    public function __construct(AuthorContractService $authorContractService, ContractService $contractService)
    {
        $this->authorContractService    = $authorContractService;
        $this->contractService          = $contractService;
    }

    /**
     * Договоры автора
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        return view('app.pages.author.contracts.index', [
            'contracts' => $this->authorContractService->getOrSearchMyContracts($request->all()),
            'request'   => $request->all()
        ]);
    }

    /**
     * Загрузка договора
     *
     * @param Request $request
     * @return StreamedResponse
     */
    public function download(Request $request)
    {
        $contract = Contract::
            whereHas('course', function ($q) {
                return $q->whereAuthorId(Auth::user()->id);
            })
            ->findOrFail($request->contract_id);

        return StorageService::download($contract->link, sprintf('Договор №%s.pdf', $contract->number));
    }
}
