<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{

    protected $table = 'student_course';

    public $timestamps = true;

    public function payment() {

        return $this->hasOne(PaymentHistory::class, 'id', 'payment_id');

    }

    public function course() {

        return $this->hasOne(Course::class, 'id', 'course_id');

    }

    public function student() {

        return $this->hasOne(User::class, 'id', 'student_id');

    }

}
