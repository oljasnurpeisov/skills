<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{

    protected $table = 'themes';

    public $timestamps = true;

    public function courses() {

        return $this->belongsTo(Course::class,'course_id', 'id');

    }
//    public function courses() {
//
//        return $this->belongsToMany(Course::class,'course_theme');
//
//    }

    public function lessons() {

        return $this->hasMany(Lesson::class,'theme_id', 'id');

    }

//    public function lessons() {
//
//        return $this->belongsToMany(Lesson::class,'theme_lesson');
//
//    }
}
