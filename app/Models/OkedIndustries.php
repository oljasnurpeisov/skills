<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OkedIndustries extends Model
{
    protected $table = 'oked_industries';

    protected $fillable = ['name_ru', 'name_kk'];
}
