<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OkedIndustries extends Model
{
    protected $table = 'oked_industries';

    protected $fillable = ['name_ru', 'name_kk'];

    /**
     * Виды деятельности
     *
     * @return HasMany
     */
    public function oked_activities(): HasMany
    {
        return $this->hasMany(OkedActivities::class, 'oked_industries_id', 'id');
    }
}
