<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLesson extends Model
{

    protected $table = 'student_lesson';

    public $timestamps = true;


    public function lesson() {

        return $this->hasOne(Lesson::class, 'id', 'lesson_id');

    }

    public function student() {

        return $this->hasOne(User::class, 'id', 'student_id');

    }

}
