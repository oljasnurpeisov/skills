<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorsController extends Controller
{
    public function authorsCatalog(Request $request, $lang)
    {
        $query = Course::where('status', '=', Course::published);

//        $topSkills = DB::table('student_skills')
//            ->select('skill_id', DB::raw('count(*) as total'))
//            ->groupBy('skill_id')
//            ->orderBy('total', 'desc')
//            ->take(30)
//            ->join('skills', 'student_skills.skill_id', '=', 'skills.id')
//            ->addSelect("skills.name_{$lang} as name")
//            ->get();
        $items = $query->paginate(6);
        return view("app.pages.general.authors.authors_catalog", [
            "items" => $items,
            'request' => $request,
        ]);
    }

    public function authorView(Request $request, $lang)
    {
        return view("app.pages.general.authors.author_view", [
        ]);
    }
}
