<?php

namespace App\Models;

use App\Services\Files\StorageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Contract
 *
 * @author kgurovoy@gmail.com
 * @package App\Models
 *
 * @property int $id
 * @property string $number
 * @property int $course_id
 * @property int $document_id
 * @property string $link
 * @property int $type
 * @property int $status
 * @property int $route_id
 * @property string $reject_comment
 * @property string $signed_at
 * @property string $updated_at
 * @property string $created_at
 *
 * @property Document $document
 */
class Contract extends Model
{
    protected $table = 'contracts';

    protected $fillable = ['number', 'course_id', 'link', 'type', 'status', 'route_id', 'reject_comment', 'document_id'];

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
     * Договор на бесплатный курс
     *
     * @param $query
     * @return Builder
     */
    public function scopeFree($query): Builder
    {
        return $query->whereType(1);
    }

    /**
     * Договор на платный курс
     *
     * @param $query
     * @return Builder
     */
    public function scopePaid($query): Builder
    {
        return $query->whereType(2);
    }

    /**
     * Договор на курс по квоте
     *
     * @param $query
     * @return Builder
     */
    public function scopeQuota($query): Builder
    {
        return $query->whereType(3);
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
     * Отклоненные администрацией
     *
     * @param $query
     * @return Builder
     */
    public function scopeRejectedByAdminOrModerator($query): Builder
    {
        return $query->where(function ($q) {
            return $q->whereStatus(5)->orWhere('status', 6);
        });
    }

    /**
     * Отклоненные админами
     *
     * @param $query
     * @return Builder
     */
    public function scopeRejectedByAdmin($query): Builder
    {
        return $query->whereStatus(5);
    }

    /**
     * Отклоненные модератором
     *
     * @param $query
     * @return Builder
     */
    public function scopeRejectedByModerator($query): Builder
    {
        return $query->whereStatus(6);
    }

    /**
     * Не отклоненные автором договора
     *
     * @param $query
     * @return Builder
     */
    public function scopeNotRejectedByAuthor($query): Builder
    {
        return $query->where('status', '!=', 4);
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
     * Не отклоненные админами
     *
     * @param $query
     * @return Builder
     */
    public function scopeNotRejectedByAdmin($query): Builder
    {
        return $query->where('status', '!=', 5);
    }

    /**
     * Бесплатный?
     *
     * @return bool
     */
    public function isFree(): bool
    {
        return $this->type === 1;
    }

    /**
     * Платный?
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->type === 2;
    }

    /**
     * По квоте?
     *
     * @return bool
     */
    public function isQuota(): bool
    {
        return $this->type === 3;
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
     * Отклонен администрацией или модератором
     *
     * @return bool
     */
    public function isRejectedByAdminOrModerator(): bool
    {
        return $this->status === 5 || $this->status === 6;
    }

    /**
     * Отклонен администрацией
     *
     * @return bool
     */
    public function isRejectedByAdmin(): bool
    {
        return $this->status === 5;
    }

    /**
     * Отклонен модератором
     *
     * @return bool
     */
    public function isRejectedByModerator(): bool
    {
        return $this->status === 6;
    }

    /**
     * Не актуален
     *
     * @return bool
     */
    public function isNotValid(): bool
    {
        return $this->status === 5;
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
            case $this->isDistributed():
                return "Расторгнут";
                break;
            case $this->isRejectedByAuthor():
                return "Отклонен автором";
                break;
            case $this->isRejectedByAdminOrModerator():
                $role_name = !empty($this->current_route) ? $this->current_route->role->name : 'Маршрут изменен';
                return "Отклонен администрацией (". $role_name .")";
                break;
            default;
                return "Сгенерирован";
                break;
        }
    }

    /**
     * Название статуса для лога
     *
     * @return string
     */
    public function getStatusNameForLog(): string
    {
        switch (true) {
            case $this->isPending():
                $role_name = !empty($this->current_route) ? $this->current_route->role->name : 'Маршрут изменен';
                return "Подписан (". $role_name .")";
                break;
            case $this->isSigned():
                return "Подписан всеми";
                break;
            case $this->isDistributed():
                return "Расторгнут";
                break;
            case $this->isRejectedByAuthor():
                return "Отклонен автором";
                break;
            case $this->isRejectedByAdminOrModerator():
                $role_name = !empty($this->current_route) ? $this->current_route->role->name : 'Маршрут изменен';
                return "Отклонен администрацией (". $role_name .")";
                break;
            default;
                return "Сгенерирован";
                break;
        }
    }

    /**
     * Название типа
     *
     * @return string
     */
    public function getTypeName(): string
    {
        switch (true) {
            case $this->isFree():
                return "Бесплатный";
                break;
            case $this->isPaid():
                return "Платный";
                break;
            case $this->isQuota():
                return "При гос. поддержке";
                break;
            default:
                return '';
        }
    }

    /**
     * Отправляем договор на подписание
     *
     * @return void
     */
    public function setPending(): void
    {
        $this->update([
            'status' => 1
        ]);
    }

    /**
     * Текущий маршрут договора
     *
     * @return HasOne
     */
    public function current_route(): HasOne
    {
        return $this->hasOne(Route::class, 'id', 'route_id');
    }

    /**
     * Get document
     * @return BelongsTo
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    /**
     * Get contract XML
     * @return string
     */
    public function xml(): string
    {
        $xml = preg_replace('/docx/', 'xml', $this->link);

        if (file_exists(StorageService::path($xml)))
            return file_get_contents(StorageService::path($xml));

        if ($this->document->content)
            return $this->document->content;
    }

    /**
     * Дата подписания автором
     *
     * @return string
     */
    public function getAuthorSignedAt(): string
    {
        if (empty($this->document)) return '-';

        $document = $this->document->with('signatures')->whereHas('signatures', function ($s) {
            return $s->whereHas('user', function ($q) {
                return $q->whereHas('role', function ($r) {
                    return $r->whereRoleId(4);
                });
            });
        })->first();

        if (empty($document)) return '-';

        return $document->signatures->first()->created_at ?? '-';
    }
}
