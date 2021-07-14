<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AVRLog extends Model
{
    protected $table = 'avr_logs';

    protected $fillable = ['avr_id', 'course_id', 'comment'];
}
