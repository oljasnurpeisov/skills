<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Skill
 *
 * @property int $id
 * @property string|null $code_skill
 * @property int|null $fl_check
 * @property string|null $name_ru
 * @property string|null $name_kk
 * @property string|null $name_en
 * @property int|null $fl_show
 * @property int|null $uid
 * @property int $is_published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Professions[] $professions
 * @property-read int|null $professions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Skill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Skill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Skill query()
 * @method static \Illuminate\Database\Eloquent\Builder|Skill whereCodeSkill($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skill whereFlCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skill whereFlShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skill whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skill whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skill whereNameKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skill whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skill whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skill whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $student_skill
 * @property-read int|null $student_skill_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Professions[] $group_professions
 * @property-read int|null $group_professions_count
 */
class Skill extends Model
{
    protected $guarded = [];

    protected $table = 'skills';

    public $timestamps = true;

    public function group_professions() {

        return $this->belongsToMany(Professions::class,'profession_skills', 'skill_id', 'profession_id');

    }

    public function courses() {

        return $this->belongsToMany(Course::class,'course_skill', 'skill_id', 'course_id');

    }

    public function student_skill() {

        return $this->belongsToMany(User::class,'student_skills', 'skill_id', 'user_id');

    }


    public function professions() {

        return $this->belongsToMany(Professions::class,'course_skill', 'skill_id', 'profession_id');

    }

}
