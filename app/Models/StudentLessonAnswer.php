<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StudentLessonAnswer
 *
 * @property int $id
 * @property int|null $student_id
 * @property int|null $lesson_id
 * @property int $type 0 - домашняя работа, 1 - курсовая работа
 * @property string|null $answer
 * @property string|null $attachments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Lesson|null $lesson
 * @property-read \App\Models\User|null $student
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer whereTextAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $student_lesson_id
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer whereStudentLessonId($value)
 * @property string|null $videos
 * @property string|null $audios
 * @property string|null $another_files
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer whereAnotherFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer whereAudios($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLessonAnswer whereVideos($value)
 */
class StudentLessonAnswer extends Model
{

    protected $table = 'student_lesson_answer';

    public $timestamps = true;


    public function lesson() {

        return $this->hasOne(Lesson::class, 'id', 'lesson_id');

    }

    public function student() {

        return $this->hasOne(User::class, 'id', 'student_id');

    }

}
