<?php

namespace Services\Contracts;


use App\Models\Contract;

class AuthorContractService
{
    /**
     * Договор курса отклонен автором
     *
     * @param int $contract_id
     * @return void
     */
    public function rejectContract(int $contract_id): void
    {
        Contract::find($contract_id)->update([
            'status' => 4
        ]);
    }
}
