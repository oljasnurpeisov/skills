<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{

    protected $table = 'lessons';

    public $timestamps = true;


//    public function themes() {
//
//        return $this->belongsToMany(Theme::class,'theme_lesson');
//
//    }

    public function themes()
    {
        return $this->hasOne(Theme::class, 'id', 'theme_id');
    }

    public function lesson_type()
    {
        return $this->belongsTo(LessonsType::class, 'type', 'id');
    }

    public function lesson_student()
    {
        return $this->hasOne(StudentLesson::class, 'lesson_id', 'id');
    }
}
