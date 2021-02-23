<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Type_of_ownership
 *
 * @property int $id
 * @property string $name_ru
 * @property string|null $name_kk
 * @property string|null $name_en
 * @property int $is_published
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Type_of_ownership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Type_of_ownership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Type_of_ownership query()
 * @method static \Illuminate\Database\Eloquent\Builder|Type_of_ownership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type_of_ownership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type_of_ownership whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type_of_ownership whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type_of_ownership whereNameKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type_of_ownership whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type_of_ownership whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Type_of_ownership extends Model
{

    protected $table = 'types_of_ownership';

    public $timestamps = true;


}
