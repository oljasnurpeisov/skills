<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CourseQuotaCost
 *
 * @property int $id
 * @property int $course_id
 * @property string $cost
 * @property int $processed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course|null $course
 * @method static \Illuminate\Database\Eloquent\Builder|CourseQuotaCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseQuotaCost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseQuotaCost query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseQuotaCost whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseQuotaCost whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseQuotaCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseQuotaCost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseQuotaCost whereProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseQuotaCost whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CourseQuotaCost extends Model
{
    // Количество человек в группе
    const group_members_count = 13;

    protected $table = 'course_quota_cost';

    public $timestamps = true;


    public function course() {

        return $this->hasOne(Course::class, 'id', 'course_id');

    }

}
