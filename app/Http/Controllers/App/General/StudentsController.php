<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentsController extends Controller
{
    public function studentsInfo(Request $request, $lang)
    {
        $topSkills = DB::table('student_skills')
            ->select('skill_id', DB::raw('count(*) as total'))
            ->groupBy('skill_id')
            ->orderBy('total', 'desc')
            ->take(30)
            ->join('skills', 'student_skills.skill_id', '=', 'skills.id')
            ->addSelect("skills.name_{$lang} as name")
            ->get();

        return view("app.pages.general.students.students_info", [
            'top_skills' => $topSkills,
        ]);
    }
}
