<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserOkedIndustry extends Model
{
    protected $table = 'user_oked_industries';

    protected $fillable = ['user_id', 'oked_industries_id'];

    /**
     * Вид деятельности
     */
    public function oked_industry(): HasOne
    {
        return $this->hasOne(OkedIndustries::class, 'id', 'oked_industries_id');
    }
}
