<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professions extends Model
{
    protected $guarded = [];

    protected $table = 'professions';

    public $timestamps = true;

    public function skills() {

        return $this->belongsToMany(Skill::class,'profession_skills', 'profession_id', 'skill_id');

    }

}
