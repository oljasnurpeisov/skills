<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StudentLesson
 *
 * @property int $id
 * @property int $student_id
 * @property int $lesson_id
 * @property int $is_access
 * @property int $is_finished
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Lesson|null $lesson
 * @property-read \App\Models\User|null $student
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLesson query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLesson whereIsAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLesson whereIsFinished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLesson whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLesson whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLesson whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
