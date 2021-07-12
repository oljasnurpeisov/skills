<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserOkedActivity extends Model
{
    protected $table = 'user_oked_activities';

    protected $fillable = ['user_id', 'oked_activities_id'];

    /**
     * Вид деятельности
     */
    public function oked_activity(): HasOne
    {
        return $this->hasOne(OkedActivities::class, 'id', 'oked_activities_id');
    }
}
