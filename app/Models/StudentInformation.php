<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentInformation extends Model
{
    const unpaid = 0;
    const paid = 1;
    const by_quota = 2;

    protected $table = 'student_information';

    public $timestamps = true;



}
