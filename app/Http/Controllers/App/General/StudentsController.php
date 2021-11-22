<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use App\Models\ProfessionalArea;
use App\Models\StudentCourse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StudentProfessions;

class StudentsController extends Controller
{
    public function studentsInfo(Request $request, $lang)
    {
        $area = $request->professional_areas;
        $course_from = $request->dateFrom;
        $course_to = $request->dateTo;
        $date_course_from = Carbon::parse($course_from ?? '01.01.2020')
            ->startOfDay()
            ->toDateTimeString();
        $date_course_to = Carbon::parse($course_to)
            ->endOfDay()
            ->toDateTimeString();

        $query = StudentProfessions::select('profession_id', DB::raw('count(*) as total'))
            ->groupBy('profession_id')
            ->orderBy('total', 'desc')
            ->join('professions', 'student_professions.profession_id', '=', 'professions.id')
            ->addSelect("professions.name_{$lang} as name")
            ->whereBetween('student_professions.created_at', [$date_course_from, $date_course_to]);
        if ($area) {
            $query = $query->where('student_professions.professional_area_id', '=', $area);
        }
        $professions = $query->get();
        $certs = $query->where('is_finished', '=', 1)->get();
        $professionalAreas = ProfessionalArea::all();
        $studentCount = StudentCourse::count();
        $withCertCount = StudentCourse::where('is_finished', '=', 1)->count();

        $query = StudentProfessions::whereBetween('student_professions.created_at', [$date_course_from, $date_course_to]);
        if ($area) {
            $studentProfessionCount = $query->where('student_professions.professional_area_id', '=', $area);
        }
        $studentProfessionCount = $query->count();
        $studentProfessionWithCertCount = $query->where('is_finished', '=', 1)->count();

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
            'professions' => $professions,
            'certs' => $certs,
            'professionalAreas' => $professionalAreas,
            'studentCount' => number_format($studentCount, 0, " ", " "),
            'withCertCount' => number_format($withCertCount, 0, " ", " "),
            'studentProfessionCount' => number_format($studentProfessionCount, 0, " ", " "),
            'studentProfessionWithCertCount' => number_format($studentProfessionWithCertCount, 0, " ", " "),
            'request' => $request,
        ]);
    }
}
