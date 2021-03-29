<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * App\Models\LessonsType
 *
 * @property int $id
 * @property string|null $name_ru
 * @property string|null $name_kk
 * @property string|null $name_en
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LessonsType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LessonsType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LessonsType query()
 * @method static \Illuminate\Database\Eloquent\Builder|LessonsType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonsType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonsType whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonsType whereNameKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonsType whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonsType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LessonsType extends Model
{

    protected $table = 'lessons_type';

    public $timestamps = true;

    public function getAttribute($key)
    {
        $array = ['name'];
        if (in_array($key, $array)) {
            if (!empty(parent::getAttribute($key . '_' . App::getLocale()))) {
                $key .= '_' . App::getLocale();
            } else {
                $key .= '_ru';
            }
        }

        return parent::getAttribute($key);
    }

}
