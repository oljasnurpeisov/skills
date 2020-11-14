<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Dialog extends Model
{
    protected $table = 'dialogs';

    public $timestamps = true;

    public function members()
    {
        return $this->belongsToMany(User::class, 'dialog_members', 'dialog_id', 'user_id');
    }

    public function opponent()
    {
        $members = $this->members;
        if (Auth::user()) {
            $members = $members->where('id', '!=', Auth::user()->id);
        }

        $member = $members->first();

        if($member->roles()->first()->slug == 'author'){
            $name = $member->author_info->name;
        }else if($member->roles()->first()->slug == 'student'){
            $name = 'Обучающийся';
        }else{
            $name = '';
        }

        return (object)[
            'id' => $member->id,
            'name' => $name,
//            'slug' => $member->slug,
//            'avatar' => $member->avatar,
        ];
    }

    public function lastMessageText()
    {
        $message = $this->messages()->orderBy('created_at', 'desc')->first();
        return $message ? $message->message : "";
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'dialog_id', 'id');
    }
}
