<?php

namespace Services\Contracts;


use App\Models\Contract;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthorContractService
{
    /**
     * @var ContractFilterService
     */
    private $contractFilterService;

    /**
     * AuthorContractService constructor.
     *
     * @param ContractFilterService $contractFilterService
     */
    public function __construct(ContractFilterService $contractFilterService)
    {
        $this->contractFilterService = $contractFilterService;
    }

    /**
     * Получаем договора автора
     *
     * @param array|null $request
     * @return LengthAwarePaginator
     */
    public function getOrSearchMyContracts(array $request = null): LengthAwarePaginator
    {
        $contracts = Contract::signed()->with('course');

        $contracts = $this->contractFilterService->search($contracts, $request);

        return $contracts->whereHas('course', function ($q) {
            return $q->whereAuthorId(Auth::user()->id);
        })->latest()->paginate(10);

    }

    /**
     * Договор курса отклонен автором
     *
     * @param Contract $contract
     * @return void
     */
    public function rejectContract(Contract $contract): void
    {
        Contract::find($contract->id)->update([
            'status' => 4
        ]);

        if ($contract->isFree() or $contract->isPaid()) {
            Session::flash('status', 'Договор ('. $contract->getTypeName() .') отклонен, курс перемещен в черновики!');

            $contract->course->update([
                'contract_status'   => 0,
                'status'            => 0
            ]);

            if ($contract->isPaid()) {
                Contract::whereCourseId($contract->course_id)->quota()->pending()->first()->update([
                    'status' => 4
                ]);
            }
        } else {
            Session::flash('status', 'Договор ('. $contract->getTypeName() .') отклонен!');
        }
    }
}
