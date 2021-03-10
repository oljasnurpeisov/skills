<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Lesson
 *
 * @property int $id
 * @property int|null $theme_id
 * @property int|null $course_id
 * @property int|null $index_number
 * @property string $name
 * @property int|null $type
 * @property int|null $end_lesson_type 0 - Тест, 1 - Домашнее задание
 * @property int $duration
 * @property string|null $image
 * @property string|null $theory
 * @property string|null $youtube_link
 * @property string|null $video
 * @property string|null $audio
 * @property string|null $files
 * @property string|null $test
 * @property string|null $text_work_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\StudentLesson|null $lesson_student
 * @property-read \App\Models\LessonsType|null $lesson_type
 * @property-read \App\Models\Theme|null $themes
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereAudio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereEndLessonType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereIndexNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereTextWorkDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereTheory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereYoutubeLink($value)
 * @mixin \Eloquent
 * @property string|null $practice
 * @property string|null $coursework_task
 * @property-read \App\Models\LessonAttachments|null $lesson_attachment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StudentLesson[] $student_lessons
 * @property-read int|null $student_lessons_count
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereCourseworkTask($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson wherePractice($value)
 */
class Lesson extends Model
{

    protected $table = 'lessons';

    public $timestamps = true;

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
        $user = \Auth::user();
        if ($user == null) {
            return null;
        }
        return $this->student_lessons()->where('student_id', '=', $user->id)->first();
//        return $this->hasOne(StudentLesson::class, 'lesson_id', 'id');
    }

    public function student_lessons()
    {

        return $this->hasMany(StudentLesson::class, 'lesson_id', 'id');

    }

    public function lesson_attachment()
    {
        return $this->hasOne(LessonAttachments::class, 'lesson_id', 'id');
    }

    public function getAvatar()
    {
        if ($this->image === null) {
            return '/assets/img/lesson-thumbnail.jpg';
        }
        return $this->image;
    }

    public function finishedLesson()
    {

        return $this->student_lessons()->where('is_finished', '=', true)->get();
    }

    public function finishedByCurrentUser()
    {
        $user = \Auth::user();

        if (!$user) {
            return false;
        }

        /** @var StudentLesson $lessonStudent */
        $lessonStudent = $this->lesson_student()->where('student_id', '=', $user->id)->first();

        return $lessonStudent != null ? $lessonStudent->is_finished : false;
    }
}
