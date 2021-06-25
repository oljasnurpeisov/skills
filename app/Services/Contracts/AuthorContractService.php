<?php

namespace Services\Contracts;


use App\Models\Contract;
use App\Models\Role;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthorContractService
{
    /**
     * Получаем договора автора
     *
     * @return LengthAwarePaginator
     */
    public function getMyContracts(): LengthAwarePaginator
    {

        return Contract::
//            signed()
            with('course')
            ->whereHas('course', function ($q) {
                return $q->whereAuthorId(Auth::user()->id);
            })
            ->latest()
            ->paginate(10);
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
