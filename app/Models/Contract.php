<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
}
