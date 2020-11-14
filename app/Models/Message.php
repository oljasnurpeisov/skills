<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $table = 'messages';

    public $timestamps = true;


    public function sender() {

        return $this->hasOne(User::class, 'id', 'sender_id');

    }

    public function dialog()
    {
        return $this->hasOne(Dialog::class, "id", "dialog_id");
    }


}
