<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Services\Contracts\ContractService;

/**
 * Class ContractsController
 *
 * @author kgurovoy@gmail.com
 * @package App\Http\Controllers\Admin
 */
class ContractsController extends Controller
{
    /**
     * @var ContractService
     */
    private $contractService;

    /**
     * ContractsController constructor.
     *
     * @param ContractService $contractService
     */
    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    /**
     * Просмотр всех договоров
     *
     * @param Request $request
     * @return View
     */
    public function all(Request $request): View
    {
        return view('admin.v2.pages.contracts.index', [
            'contracts' => $this->contractService->getOrSearch($request->keywords),
            'keywords'  => $request->keywords,
            'title'     => 'Договоры'
        ]);
    }

    /**
     * Подписанные договора
     *
     * @param Request $request
     * @return View
     */
    public function signed(Request $request): View
    {
        return view('admin.v2.pages.contracts.index', [
            'contracts' => $this->contractService->getOrSearch($request->keywords, 'signed'),
            'keywords'  => $request->keywords,
            'title'     => 'Подписанные договора'
        ]);
    }

    /**
     * Расторгнутые договора
     *
     * @param Request $request
     * @return View
     */
    public function distributed(Request $request): View
    {
        return view('admin.v2.pages.contracts.index', [
            'contracts' => $this->contractService->getOrSearch($request->keywords, 'distributed'),
            'keywords'  => $request->keywords,
            'title'     => 'Расторгнутые договора'
        ]);
    }

    /**
     * Отклонены автором
     *
     * @param Request $request
     * @return View
     */
    public function rejectedByAuthor(Request $request): View
    {
        return view('admin.v2.pages.contracts.index', [
            'contracts' => $this->contractService->getOrSearch($request->keywords, 'rejectedByAuthor'),
            'keywords'  => $request->keywords,
            'title'     => 'Отклонены автором'
        ]);
    }

    /**
     * Ожидающие подписания
     *
     * @param Request $request
     * @return View
     */
    public function pending(Request $request): View
    {
        return view('admin.v2.pages.contracts.index', [
            'contracts' => $this->contractService->getOrSearch($request->keywords, 'pending'),
            'keywords'  => $request->keywords,
            'title'     => 'Ожидающие подписания'
        ]);
    }
}
