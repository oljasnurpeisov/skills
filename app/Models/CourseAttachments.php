<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\CourseAttachments
 *
 * @property int $id
 * @property int $course_id
 * @property string|null $videos_link
 * @property string|null $videos_poor_vision_link
 * @property string|null $videos
 * @property string|null $videos_poor_vision
 * @property string|null $audios
 * @property string|null $audios_poor_vision
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course|null $course
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments whereAudios($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments whereAudiosPoorVision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments whereVideos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments whereVideosLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments whereVideosPoorVision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments whereVideosPoorVisionLink($value)
 * @mixin \Eloquent
 * @property string|null $videos_poor_hearing_link
 * @property string|null $videos_poor_hearing
 * @property string|null $audios_poor_hearing
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments whereAudiosPoorHearing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments whereVideosPoorHearing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseAttachments whereVideosPoorHearingLink($value)
 */
class CourseAttachments extends Model
{

    protected $table = 'course_attachments';

    public $timestamps = true;


    public function course() {

        return $this->hasOne(Course::class, 'id', 'course_id');

    }
}
