<?php

namespace Services\Contracts;

use App\Models\Contract;

class ContractStatusService
{
    /**
     * Set pending
     */
    public function setPending(int $id)
    {
        Contract::find($id)->setPending();
    }

}
