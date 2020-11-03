<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    const draft = 0;
    const onCheck = 1;
    const unpublished = 2;
    const published = 3;
    const deleted = 4;

    protected $table = 'courses';

    public $timestamps = true;


    public function user() {

        return $this->hasOne(User::class, 'id', 'author_id');

    }

    public function themes() {

        return $this->belongsToMany(Theme::class,'course_theme');

    }

    public function skills() {

        return $this->belongsToMany(Skill::class,'course_skill', 'course_id', 'skill_id');

    }
}
