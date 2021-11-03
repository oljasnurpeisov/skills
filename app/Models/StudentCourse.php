<?php

namespace App\Models;

use App\Libraries\Kalkan\Certificate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StudentCourse
 *
 * @property int $id
 * @property int|null $student_id
 * @property int|null $course_id
 * @property int|null $payment_id
 * @property int $paid_status 0 - не оплачен, 1 - оплачен, 2 - доступен по квоте
 * @property float $progress
 * @property int $is_finished
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course|null $course
 * @property-read \App\Models\PaymentHistory|null $payment
 * @property-read \App\Models\User|null $student
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCourse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCourse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCourse query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCourse whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCourse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCourse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCourse whereIsFinished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCourse wherePaidStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCourse wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCourse whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCourse whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCourse whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $courses
 * @property-read int|null $courses_count
 * @property int|null $unemployed_status
 * @property \Illuminate\Support\Carbon|null $unemployed_date
 * @property int $is_qualificated
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCourse whereIsQualificated($value)
 */
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

    public function courses() {

        return $this->hasMany(Course::class, 'id', 'course_id');

    }

    public function student() {

        return $this->hasOne(User::class, 'id', 'student_id');

    }

    public function student_info()
    {
        return $this->hasOne(StudentInformation::class, 'user_id', 'student_id');
    }

    public function certificate()
    {
        $certificate = StudentCertificate::where('user_id', '=', $this->student_id)
            ->where('course_id', '=', $this->course_id)
            ->first();

        return $certificate ?? null;
    }

    public function student_first_lesson()
    {
        $firstLesson = Lesson::where('course_id', '=', $this->course_id)
            ->orderBy('theme_id', 'asc')
            ->orderBy('index_number', 'asc')
            ->first();

        $studentLesson = StudentLesson::where('lesson_id', '=', $firstLesson->id)
            ->where('student_id', '=', $this->student_id)
            ->first();
        return $studentLesson ?? null;
    }

    public function attempts()
    {
        $attempts = StudentLessonAnswerAttempt::where('course_id', '=', $this->course_id)
            ->where('student_id', '=', $this->student_id)
            ->get();

        return $attempts ?? null;
    }

}
