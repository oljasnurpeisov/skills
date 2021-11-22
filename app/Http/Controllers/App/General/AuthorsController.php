<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorsController extends Controller
{
    public function authorsCatalog(Request $request, $lang)
    {
        $term = $request->search ? $request->search : '';
        $query = User::whereHas('roles', function ($q) {
            $q->whereSlug('author');
        });
        if ($term) {
            $query = $query->where('company_name', 'like', '%' . $term . '%');
        }
        $items = $query->paginate(8);
        foreach ($items as $item) {
            $courses = $item->courses()->get();
            // Все оценки всех курсов
            $rates = [];
            foreach ($courses as $course) {
                foreach ($course->rate as $rate) {
                    array_push($rates, $rate->rate);
                }
            }
            // Все ученики автора
            $author_students = [];
            foreach ($courses as $course) {
                foreach ($course->course_members as $member) {
                    $author_students[$member['student_id']][] = $member;
                }
            }
            // Оценка автора исходя из всех оценок
            if (count($rates) == 0) {
                $average_rates = 0;
            } else {
                $average_rates = array_sum($rates) / count($rates);
            }

            $item->rates = $rates;
            $item->courses = $courses;
            $item->author_students = $author_students;
            $item->average_rates = $average_rates;
        }
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
