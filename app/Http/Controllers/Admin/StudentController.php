<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kato;
use App\Models\RegionTree;
use App\Models\Role;
use App\Models\StudentInformation;
use App\Models\User;

use App\Services\Users\UserFilterService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * @var UserFilterService
     */
    private $userFilterService;

    /**
     * UserController constructor.
     *
     * @param UserFilterService $userFilterService
     */
    public function __construct(UserFilterService $userFilterService)
    {
        $this->userFilterService = $userFilterService;
    }

    public function index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'student');
        })->orderBy('id', 'desc');
        if ($term) {
            $query = $query->where('email', 'like', '%' . $term . '%');
        }

        $query = $this->userFilterService->getOrSearch($query, $request->all());

        $items = $query->paginate();

        return view('admin.v2.pages.students.index', [
            'items' => $items,
            'term' => $term,
            'request' => $request->all()
        ]);
    }

    public function edit($lang, User $item)
    {
        $roles = Role::orderBy('name', 'asc')->get();
        $user_information = StudentInformation::where('user_id', '=', $item->id)->first();
        $coduozCaption = RegionTree::getRegionCaption($lang, $user_information->coduoz) ?? '';
        $regionCaption = Kato::where('te',  $user_information->region_id)->first()->rus_name ?? '';
        return view('admin.v2.pages.students.view', [
            'item' => $item,
            'roles' => $roles,
            'user_information' => $user_information,
            "coduozCaption" => $coduozCaption,
            "regionCaption" => $regionCaption,
        ]);
    }

}
