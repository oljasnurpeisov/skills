<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LessonAttachments
 *
 * @property int $id
 * @property int $lesson_id
 * @property string|null $youtube_link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $videos_link
 * @property string|null $videos_poor_vision_link
 * @property string|null $videos
 * @property string|null $videos_poor_vision
 * @property string|null $audios
 * @property string|null $audios_poor_vision
 * @property-read \App\Models\Lesson|null $lesson
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments query()
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereAudios($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereAudiosPoorVision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereVideos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereVideosLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereVideosPoorVision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereVideosPoorVisionLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereYoutubeLink($value)
 * @mixin \Eloquent
 * @property string|null $another_files
 * @property string|null $another_files_poor_vision
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereAnotherFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereAnotherFilesPoorVision($value)
 * @property string|null $videos_poor_hearing_link
 * @property string|null $videos_poor_hearing
 * @property string|null $audios_poor_hearing
 * @property string|null $another_files_poor_hearing
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereAnotherFilesPoorHearing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereAudiosPoorHearing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereVideosPoorHearing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonAttachments whereVideosPoorHearingLink($value)
 */
class LessonAttachments extends Model
{

    protected $table = 'lesson_attachments';

    public $timestamps = true;


    public function lesson() {

        return $this->hasOne(Lesson::class, 'id', 'lesson_id');

    }
}
