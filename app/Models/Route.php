<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Route extends Model
{
    protected $table = 'routes';

    protected $fillable = ['type', 'role_id', 'sort'];

    /**
     * Маршруты на договора (платные курсы)
     *
     * @param $q
     * @return Builder
     */
    public function scopeContractPaid($q): Builder
    {
        return $q->whereType(1);
    }

    /**
     * Маршруты на договора (курсы по квоте)
     *
     * @param $q
     * @return Builder
     */
    public function scopeContractQuota($q): Builder
    {
        return $q->whereType(2);
    }

    /**
     * Маршруты на АВР
     *
     * @param $q
     * @return Builder
     */
    public function scopeAvr($q): Builder
    {
        return $q->whereType(3);
    }

    /**
     * Роли
     *
     * @return HasOne
     */
    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
