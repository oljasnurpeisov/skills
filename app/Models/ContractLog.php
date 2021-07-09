<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ContractLog extends Model
{
    protected $table = 'contract_logs';

    protected $fillable = ['contract_id', 'course_id', 'comment'];

    /**
     * Договор
     *
     * @return HasOne
     */
    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class, 'id', 'contract_id');
    }
}
