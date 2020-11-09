<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRate extends Model
{

    protected $table = 'course_rate';

    public $timestamps = true;


    public function course() {

        return $this->hasOne(Course::class, 'id', 'course_id');

    }

    public function student() {

        return $this->hasOne(User::class, 'id', 'student_id');

    }
}
