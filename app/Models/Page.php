<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Page
 *
 * @property int $id
 * @property string|null $page_alias
 * @property string|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page wherePageAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $data_ru
 * @property string|null $data_kk
 * @property string|null $data_en
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereDataEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereDataKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereDataRu($value)
 */
class Page extends Model
{

    protected $table = 'pages';

    public $timestamps = true;


}
