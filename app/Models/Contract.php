<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Contract
 *
 * @author kgurovoy@gmail.com
 * @package App\Models
 */
class Contract extends Model
{
    protected $table = 'contracts';

    protected $fillable = ['course_id', 'link', 'status'];

    /**
     * Course
     *
     * @return HasOne
     */
    public function course(): HasOne
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }

    /**
     * Отклоненные автором договора
     *
     * @param $query
     * @return Builder
     */
    public function scopeRejectedByAuthor($query): Builder
    {
        return $query->whereStatus(4);
    }

    /**
     * Расторгнутые договора
     *
     * @param $query
     * @return Builder
     */
    public function scopeDistributed($query): Builder
    {
        return $query->whereStatus(3);
    }

    /**
     * Подписанные договора
     *
     * @param $query
     * @return Builder
     */
    public function scopeSigned($query): Builder
    {
        return $query->whereStatus(2);
    }

    /**
     * Ожидающие подписания договора
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
     * Расторгнут?
     *
     * @return bool
     */
    public function isDistributed(): bool
    {
        return $this->status === 3;
    }

    /**
     * Отклонен автором?
     *
     * @return bool
     */
    public function isRejectedByAuthor(): bool
    {
        return $this->status === 4;
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
                return 'Ожидает подписания';
                break;
            case $this->isSigned():
                return 'Подписан';
                break;
            case $this->isDistributed():
                return 'Расторгнут';
                break;
            case $this->isRejectedByAuthor():
                return 'Отклонен автором';
                break;
            default;
                return 'Сгенерирован';
                break;
        }
    }
}
