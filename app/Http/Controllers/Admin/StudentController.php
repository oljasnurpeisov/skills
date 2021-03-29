<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\StudentInformation;
use App\Models\User;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'student');
        })->orderBy('id', 'desc');
        if ($term) {
            $query = $query->where('email', 'like', '%' . $term . '%');
        }

        $items = $query->paginate();

        return view('admin.v2.pages.students.index', [
            'items' => $items,
            'term' => $term,
        ]);
    }

    public function edit($lang, User $item)
    {
        $roles = Role::orderBy('name', 'asc')->get();
        $user_information = StudentInformation::where('user_id', '=', $item->id)->first();
        return view('admin.v2.pages.students.view', [
            'item' => $item,
            'roles' => $roles,
            'user_information' => $user_information
        ]);
    }

}
