<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PaymentHistory
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $amount
 * @property int|null $status 0 - Неуспешная транзакция, 1 - Успешная транзакция, 3 - Транзакция отменена или был совершен возврат
 * @property string|null $data
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentHistory whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentHistory whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentHistory whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaymentHistory extends Model
{

    protected $table = 'payment_history';

    public $timestamps = true;


}
