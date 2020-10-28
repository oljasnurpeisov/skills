<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $table = 'notifications';

    public $timestamps = true;


    public function users() {

        return $this->belongsToMany(User::class,'user_notifications');

    }
}
