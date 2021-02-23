<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseQuotaCost extends Model
{
    // Стоимость 1 часа
    const hour_cost = 2001.05;
    // Количество человек в группе
    const group_members_count = 13;

    protected $table = 'course_quota_cost';

    public $timestamps = true;


    public function course() {

        return $this->hasOne(Course::class, 'id', 'course_id');

    }

}
