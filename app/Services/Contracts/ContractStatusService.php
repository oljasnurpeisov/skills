<?php

namespace Services\Contracts;

use App\Models\Contract;

class ContractStatusService
{
    /**
     *
     */
    public function setPending(int $id)
    {
        Contract::find($id)->setPending();
    }

}
