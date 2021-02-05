<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Dialog
 *
 * @property int $id
 * @property int $is_ts
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messages
 * @property-read int|null $messages_count
 * @method static \Illuminate\Database\Eloquent\Builder|Dialog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dialog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dialog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dialog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dialog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dialog whereIsTs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dialog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
        $tech_support_user = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'tech_support');
        })->first();
        $members = $this->members;
        if (Auth::user()->can('admin.tech_support')) {
            $members = $members->where('id', '!=', $tech_support_user->id);
        } else {
            $members = $members->where('id', '!=', Auth::user()->id);
        }

        $member = $members->first();

        if ($member->roles()->first()->slug == 'author') {
            $name = $member->author_info->name;
            $avatar = $member->author_info->getAvatar();
            $slug = '';
        }else if($member->roles()->first()->slug == 'student'){
            $name = $member->student_info->name ?? __('default.pages.profile.student_title');
            $avatar = $member->student_info->getAvatar();
            $slug = '';
        } else {
            $name = $member->name;
            $avatar = '';
            $slug = 'tech_support';
        }

        return (object)[
            'id' => $member->id,
            'name' => $name,
            'avatar' => $avatar,
            'slug' => $slug,
        ];
    }

    public function lastMessageText()
    {
        $message = $this->messages()->orderBy('created_at', 'desc')->first();
        return $message ? $message->message : "";
    }

    public function lastMessageDate()
    {
        $date = $this->messages()->orderBy('created_at', 'desc')->first();
        return $date ? $date->created_at : "";
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'dialog_id', 'id');
    }
}
