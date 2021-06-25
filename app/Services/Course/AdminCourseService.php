<?php

namespace Services\Course;

use App\Models\Contract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Services\Contracts\AuthorContractService;
use Services\Contracts\ContractServiceRouting;
use Services\Documents\DocumentService;

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
     * ContractService constructor.
     *
     * @param AuthorContractService $authorContractService
     * @param ContractServiceRouting $contractServiceRouting
     */
    public function __construct(AuthorContractService $authorContractService, ContractServiceRouting $contractServiceRouting)
    {
        $this->authorContractService    = $authorContractService;
        $this->contractServiceRouting   = $contractServiceRouting;
    }

    /**
     * Заглушка, пока нет ЭЦП
     *
     * @TODO: REMOVE THIS!!!
     *
     * @param int $contract_id
     */
    public function acceptContract(int $contract_id)
    {
        $contract = Contract::whereHas('current_route', function ($r) {
                return $r->whereRoleId(Auth::user()->role->role_id);
            })
            ->findOrFail($contract_id);

        $this->contractServiceRouting->toNextRoute($contract);
    }
}
