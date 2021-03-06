<?php

namespace App\Models;

use App\Services\Files\StorageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

/**
 * Class AVR
 * @package App\Models
 *
 * @property int $id
 * @property string $number
 * @property int $contract_id
 * @property int $course_id
 * @property string $link
 * @property string $invoice_link
 * @property int $status
 * @property int $sum
 * @property string $start_at
 * @property string $end_at
 * @property int $route_id
 * @property int $document_id
 * @property string $created_at
 * @property string $signed_at
 * @property string $author_signed_at
 * @property string $updated_at
 *
 * @property Document $document
 */
class AVR extends Model
{
    protected $table = 'avrs';

    protected $fillable = ['contract_id', 'number', 'course_id', 'link', 'status', 'sum', 'start_at', 'end_at', 'route_id', 'invoice_link', 'author_signed_at'];

    protected $dates = ['start_at', 'end_at', 'author_signed_at'];

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
     * @param string $lang
     * @return string
     */
    public function getStatusName(string $lang = 'ru'): string
    {
        switch (true) {
            case $this->isPending():
                $role_name = !empty($this->current_route) ? $this->current_route->role->name : 'Маршрут изменен';
                return trans('default.pages.avr.on_signing', [], $lang) .' ('. $role_name .')';
                break;
            case $this->isSigned():
                return trans('default.pages.avr.signed', [], $lang);
                break;
            default;
                return "Сгенерирован";
                break;
        }
    }

    /**
     * Название статуса для лога
     *
     * @param int $contract_status
     * @param null|int $current_route
     * @return string
     */
    public function getStatusNameForLog(int $contract_status, int $current_route = null): string
    {
        switch (true) {
            case $contract_status === 1:
                $route = Route::find($current_route);
                $role_name = !empty($route) ? $route->role->name : 'Маршрут изменен';
                return "Подписан (". $role_name .")";
                break;
            case $contract_status === 2:
                return "Подписан всеми";
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

    /**
     * Текущий пользователь подписант?
     *
     * @return bool
     */
    public function isSignator(): bool
    {
        return $this->current_route->role_id === Auth::user()->role->role_id and $this->isPending();
    }

    /**
     * Get document
     *
     * @return BelongsTo
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    /**
     * Дата принятия работ
     *
     * @todo may be use signed_at attribute instead?
     *
     * @return string
     */
    public function getSignedAt(): string
    {
        return (!empty($this->document->lastSignature) and $this->isSigned()) ? $this->document->lastSignature->created_at->format('d.m.Y H:i:s') : '-';
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

    /**
     * Get act XML
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
}
