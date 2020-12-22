<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Professions
 *
 * @property int $id
 * @property string $cod
 * @property string|null $cod_nkz
 * @property string|null $name_ru
 * @property string|null $name_kk
 * @property string|null $name_en
 * @property string|null $text_ru
 * @property string|null $text_kk
 * @property string|null $text_en
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Skill[] $skills
 * @property-read int|null $skills_count
 * @method static \Illuminate\Database\Eloquent\Builder|Professions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Professions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Professions query()
 * @method static \Illuminate\Database\Eloquent\Builder|Professions whereCod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professions whereCodNkz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professions whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professions whereNameKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professions whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professions whereTextEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professions whereTextKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professions whereTextRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professions whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $parent_id
 * @property string|null $code
 * @method static \Illuminate\Database\Eloquent\Builder|Professions whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professions whereParentId($value)
 */
class Professions extends Model
{
    protected $guarded = [];

    protected $table = 'professions';

    public $timestamps = true;

    public function skills() {

        return $this->belongsToMany(Skill::class,'profession_skills', 'profession_id', 'skill_id');

    }

}
