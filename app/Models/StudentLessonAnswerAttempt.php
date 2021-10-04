<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StudentLessonAnswerAttempt
 *
 * @property int $id
 * @property int|null $student_id
 * @property int|null $course_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswerAttempt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswerAttempt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswerAttempt query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswerAttempt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswerAttempt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswerAttempt whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswerAttempt whereStudentId($value)
 * @mixin \Eloquent
 * @property int|null $student_lesson_id
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswerAttempt whereStudentLessonId($value)
 */
class StudentLessonAnswerAttempt extends Model
{
    protected $table = 'student_lesson_answer_attempts';

    public $timestamps = true;
}
