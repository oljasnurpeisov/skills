<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSkill extends Model
{
    protected $table = "course_skill";

    protected $fillable = ['course_id', 'skill_id', 'profession_id', 'professional_area_id'];
}
