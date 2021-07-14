<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AVRLog extends Model
{
    protected $table = 'avr_logs';

    protected $fillable = ['avr_id', 'course_id', 'comment'];

    /**
     * АВР
     *
     * @return HasOne
     */
    public function avr(): HasOne
    {
        return $this->hasOne(AVR::class, 'id', 'avr_id');
    }
}
