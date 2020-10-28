<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

    protected $table = 'courses';

    public $timestamps = true;


    public function users() {

        return $this->belongsToMany(User::class,'course_user');

    }

    public function themes() {

        return $this->belongsToMany(Theme::class,'course_theme');

    }
}
