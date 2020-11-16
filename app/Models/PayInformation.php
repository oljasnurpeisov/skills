<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PayInformation
 *
 * @property int $id
 * @property int $user_id
 * @property string $merchant_certificate_id
 * @property string $merchant_name
 * @property string $private_key_path
 * @property string $private_key_pass
 * @property string $public_key_path
 * @property int $merchant_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PayInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PayInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayInformation whereMerchantCertificateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayInformation whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayInformation whereMerchantName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayInformation wherePrivateKeyPass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayInformation wherePrivateKeyPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayInformation wherePublicKeyPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayInformation whereUserId($value)
 * @mixin \Eloquent
 */
class PayInformation extends Model
{

    protected $table = 'user_pay_information';

    public $timestamps = true;


}
