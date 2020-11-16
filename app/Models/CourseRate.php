<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CourseRate
 *
 * @property int $id
 * @property int $course_id
 * @property int $student_id
 * @property int $rate
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course|null $course
 * @property-read \App\Models\User|null $student
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRate whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRate whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRate whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRate whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
