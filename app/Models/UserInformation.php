<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserInformation
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string|null $avatar
 * @property string|null $specialization
 * @property string|null $about
 * @property string|null $phone_1
 * @property string|null $phone_2
 * @property string|null $site_url
 * @property string|null $vk_link
 * @property string|null $fb_link
 * @property string|null $instagram_link
 * @property string|null $certificates
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereCertificates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereFbLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereInstagramLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation wherePhone1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation wherePhone2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereSiteUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereVkLink($value)
 * @mixin \Eloquent
 */
class UserInformation extends Model
{

    protected $table = 'user_information';

    public $timestamps = true;


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
