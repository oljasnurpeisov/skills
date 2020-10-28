<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    protected $table = 'permissions';

    public $timestamps = true;

    public function roles() {

        return $this->belongsToMany(Role::class,'permission_role');

    }

//    public function users() {
//
//        return $this->belongsToMany(User::class,'users_permissions');
//
//    }

}
