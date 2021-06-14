<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contract extends Model
{
    protected $table = 'contracts';

    protected $fillable = ['course_id', 'link', 'status'];

    /**
     * Course
     *
     * @return HasOne
     */
    public function course(): HasOne
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }
}
