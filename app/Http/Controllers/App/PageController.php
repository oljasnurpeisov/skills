<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Type_of_ownership;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PageController extends Controller
{
    public function index(Request $request, $lang = "ru")
    {
//        $types_of_ownership =
        return view("welcome", [
            "items" => [],
        ]);
    }
}
