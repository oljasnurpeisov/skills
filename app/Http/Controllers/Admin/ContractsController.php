<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Services\Contracts\ContractService;

/**
 * Class ContractsController
 *
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
    public function index(Request $request): View
    {
        return view('admin.v2.pages.contracts.index', [
            'contracts' => $this->contractService->getOrSearch($request->keywords),
            'keywords'  => $request->keywords
        ]);
    }
}
