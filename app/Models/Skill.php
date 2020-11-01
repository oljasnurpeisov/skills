<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $guarded = [];

    protected $table = 'skills';

    public $timestamps = true;

    public function professions() {

        return $this->belongsToMany(Professions::class,'profession_skills', 'skill_id', 'profession_id');

    }

    public function courses() {

        return $this->belongsToMany(Course::class,'course_skill', 'skill_id', 'course_id');

    }
}
