<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProfessionSkill extends Model
{
    protected $table = 'profession_skills';

    /**
     * Профессия
     *
     * @return HasOne
     */
    public function profession()
    {
        return $this->hasOne(Professions::class, 'id', 'profession_id');
    }
}
