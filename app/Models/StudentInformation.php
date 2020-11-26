<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StudentInformation
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $uid
 * @property string|null $profession_code
 * @property int|null $unemployed_status
 * @property int $quota_count
 * @property string|null $avatar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StudentInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentInformation whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentInformation whereProfessionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentInformation whereQuotaCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentInformation whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentInformation whereUnemployedStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentInformation whereUserId($value)
 * @mixin \Eloquent
 */
class StudentInformation extends Model
{
    const unpaid = 0;
    const paid = 1;
    const by_quota = 2;

    protected $table = 'student_information';

    public $timestamps = true;


    public function getAvatar()
    {
        if ($this->avatar === null) {
            return '/assets/img/author-thumbnail.png';
        }
        return $this->avatar;
    }

}
