<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Course
 *
 * @property int $id
 * @property int $author_id
 * @property string $name
 * @property int|null $lang 0 - казахский, 1 - русский
 * @property int $is_paid
 * @property int $is_access_all
 * @property string $cost
 * @property string|null $profit_desc
 * @property string|null $teaser
 * @property string|null $description
 * @property string|null $course_includes
 * @property string|null $image
 * @property string|null $youtube_link
 * @property string|null $video
 * @property string|null $audio
 * @property int|null $certificate_id
 * @property int $status 0 - черновик, 1 - на проверке, 2 - не опубликован, 3 - опубликован, 4 - удален
 * @property int $quota_status 0 - без квоты, 1 - заявка на квоту отправлена автору, 2 - доступен по квоте, 3 - квота отклонена автором
 * @property string|null $quota_contract_number
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StudentCourse[] $course_members
 * @property-read int|null $course_members_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lesson[] $lessons
 * @property-read int|null $lessons_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CourseRate[] $rate
 * @property-read int|null $rate_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Skill[] $skills
 * @property-read int|null $skills_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Theme[] $themes
 * @property-read int|null $themes_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereAudio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCertificateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCourseIncludes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereIsAccessAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereProfitDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereQuotaContractNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereQuotaStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereTeaser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereYoutubeLink($value)
 * @mixin \Eloquent
 * @property int $is_poor_vision
 * @property-read \App\Models\CourseAttachments|null $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereIsPoorVision($value)
 * @property int|null $previous_status
 * @method static \Illuminate\Database\Eloquent\Builder|Course wherePreviousStatus($value)
 */
class Course extends Model
{
    // Статусы курса
    const draft = 0;
    const onCheck = 1;
    const unpublished = 2;
    const published = 3;
    const deleted = 4;

    protected $table = 'courses';

    public $timestamps = true;


    public function user() {

        return $this->hasOne(User::class, 'id', 'author_id');

    }

    public function users() {

        return $this->hasMany(User::class,'id', 'author_id');

    }

    public function themes() {

        return $this->hasMany(Theme::class,'course_id', 'id');

    }

    public function lessons() {

        return $this->hasMany(Lesson::class,'course_id', 'id');

    }

    public function skills() {

        return $this->belongsToMany(Skill::class,'course_skill', 'course_id', 'skill_id');

    }

    public function professions() {

        return $this->belongsToMany(Professions::class,'course_skill', 'course_id', 'profession_id');

    }

    public function course_members() {

        return $this->hasMany(StudentCourse::class,'course_id', 'id');

    }

    public function rate() {

        return $this->hasMany(CourseRate::class,'course_id', 'id');

    }

    public function attachments() {

        return $this->hasOne(CourseAttachments::class,'course_id', 'id');

    }

    public function getAvatar()
    {
        if ($this->image === null) {
            return '/assets/img/course-thumbnail.jpg';
        }
        return $this->image;
    }

    public function courseWork()
    {
        return $this->lessons()->where('type', '=', 3)->first();
    }

    public function finalTest()
    {
        return $this->lessons()->where('type', '=', 4)->first();
    }

    public function professionsBySkills()
    {
        $professions = Professions::whereHas('skills', function ($q) {
            $q->whereIn('skills.id', $this->skills->pluck('id')->toArray());
        });

        return $professions;
    }

    public function studentCertificate() {

        $certificate = StudentCertificate::where('course_id', '=', $this->id)
            ->where('user_id', '=', \Auth::user()->id)->first();

        return $certificate;

    }

}
