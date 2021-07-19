<?php

namespace App\Http\Controllers\App\Author;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Services\Files\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PhpOffice\PhpWord\Exception\Exception;
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
     * Просмотр договора
     *
     * @param Request $request
     * @return View
     */
    public function view(Request $request)
    {
        return view('app.pages.author.contracts.show', [
            'contract' => Contract::whereHas('course', function ($q) {
                return $q->whereAuthorId(Auth::user()->id);
            })->findOrFail($request->contract_id)
        ]);
    }

    /**
     * Предпросмотр договора
     *
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function getContractHtml(Request $request): string
    {
        return view('app.pages.author.courses.contractDoc', [
            'contract' => $this->contractService->contractToHtml($request->contract_id)
        ]);
    }

    /**
     * Просмотр подписанного договора
     *
     * @param Request $request
     * @return string
     */
    public function getContractPdf(Request $request)
    {
        /** @var Contract $contract */
        $contract = Contract::where('id', $request->contract_id)->firstOrFail();

        if ($contract && $contract->link) {
            return StorageService::preview($contract->link);
        }

        abort(404);
    }

    /**
     * Загрузка договора
     *
     * @param Request $request
     * @return StreamedResponse|BinaryFileResponse
     * @throws Exception
     */
    public function download(Request $request)
    {
        $contract = Contract::
            whereHas('course', function ($q) {
                return $q->whereAuthorId(Auth::user()->id);
            })
            ->findOrFail($request->contract_id);

        if (!empty($contract->link) && pathinfo($contract->link)['extension'] === 'pdf') {
            return StorageService::download($contract->link, sprintf('Договор №%s.pdf', $contract->number));
        } else {
            $path = $this->contractService->contractToPdf($request->contract_id, false, true);

            return response()->download(StorageService::path($path), sprintf('Соглашение №%s.pdf', $contract->number))->deleteFileAfterSend(true);
        }
    }
}
