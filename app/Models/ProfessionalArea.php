<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProfessionalArea
 *
 * @property int $id
 * @property string $code
 * @property string $name_ru
 * @property string $name_kk
 * @property string $name_en
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessionalArea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessionalArea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessionalArea query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessionalArea whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessionalArea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessionalArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessionalArea whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessionalArea whereNameKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessionalArea whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessionalArea whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Professions[] $professions
 * @property-read int|null $professions_count
 */
class ProfessionalArea extends Model
{
    protected $guarded = [];

    protected $table = 'professional_areas';

    public $timestamps = true;

    public function professions() {

        return $this->belongsToMany(Professions::class,'professional_area_professions', 'professional_area_id', 'profession_id');

    }

}
