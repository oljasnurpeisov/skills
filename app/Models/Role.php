<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $table = 'roles';

    public $timestamps = true;

    public function permissions() {

        return $this->belongsToMany(Permission::class,'permission_role');

    }

    public function users() {

        return $this->belongsToMany(User::class,'role_user');

    }

}
