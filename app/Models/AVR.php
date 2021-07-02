<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AVR extends Model
{
    protected $table = 'avrs';

    protected $fillable = ['contract_id', 'number', 'course_id', 'link', 'status', 'sum', 'start_at', 'end_at', 'route_id', 'invoice_link'];

    protected $dates = ['start_at', 'end_at'];

    /**
     * Курс
     *
     * @return HasOne
     */
    public function course(): HasOne
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }

    /**
     * Подписанные АВР
     *
     * @param $query
     * @return Builder
     */
    public function scopeSigned($query): Builder
    {
        return $query->whereStatus(2);
    }

    /**
     * Ожидающие подписания АВР
     *
     * @param $query
     * @return Builder
     */
    public function scopePending($query): Builder
    {
        return $query->whereStatus(1);
    }

    /**
     * Ожидает подписания?
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 1;
    }

    /**
     * Подписан?
     *
     * @return bool
     */
    public function isSigned(): bool
    {
        return $this->status === 2;
    }

    /**
     * Название статуса
     *
     * @return string
     */
    public function getStatusName(): string
    {
        switch (true) {
            case $this->isPending():
                $role_name = !empty($this->current_route) ? $this->current_route->role->name : 'Маршрут изменен';
                return "Ожидает подписания (". $role_name .")";
                break;
            case $this->isSigned():
                return "Подписан";
                break;
            default;
                return "Сгенерирован";
                break;
        }
    }

    /**
     * Договор
     *
     * @return HasOne
     */
    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class, 'id', 'contract_id');
    }

    /**
     * Текущий маршрут АВР
     *
     * @return HasOne
     */
    public function current_route(): HasOne
    {
        return $this->hasOne(Route::class, 'id', 'route_id');
    }
}
