<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

/**
 * --------------------------------------------------------------------------
 *  RoleController
 * --------------------------------------------------------------------------
 *
 */
class RoleController extends Controller
{
    public function index(Request $request)
    {
        $main_roles = ['admin', 'author', 'student', 'tech_support'];

        $term = $request->term ? $request->term : '';

        $query = Role::orderBy('id', 'desc');
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }
        $items = $query->paginate();

        return view('admin.v2.pages.roles.index', [
            'items' => $items,
            'term' => $term,
            'main_roles' => $main_roles
        ]);
    }

    public function create(Request $request)
    {
        $item = new Role();
        $permissions = Permission::orderBy('id', 'asc')->get();
        return view('admin.v2.pages.roles.create', [
            'item' => $item,
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:255|unique:roles,name',
            'slug' => 'required|min:3|max:255|unique:roles,slug'
        ]);

        $item = new Role();
        $item->name = $request->name;
        $item->slug = $request->slug;
        $item->description = $request->description;
        $item->save();

        $item->permissions()->sync($request->get('permissions', []));

//        Buffet::log('add', 'roles', $item->id, $item->name);

        return redirect('/'.app()->getLocale().'/admin/role/' . $item->id)->with('status', __('admin.notifications.record_stored'));
    }

    public function edit($lang, Role $item)
    {
        $main_roles = ['admin', 'author', 'student', 'tech_support'];

        $permissions = Permission::orderBy('id', 'asc')->get();
        return view('admin.v2.pages.roles.edit', [
            'item' => $item,
            'permissions' => $permissions,
            'main_roles' => $main_roles
        ]);
    }

    public function update(Request $request, $lang,Role $item)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:255|unique:roles,name,' . $item->id,
        ]);

        $item->name = $request->name;
        $item->description = $request->description;
        $item->save();

//        $item->syncPermissions($request->permissions);
        $item->permissions()->sync($request->get('permissions', []));

//        Buffet::log('edit', 'roles', $item->id, $item->name);

        return redirect('/'.app()->getLocale().'/admin/role/' . $item->id)->with('status', __('admin.notifications.record_updated'));
    }

    public function delete($lang, Role $item)
    {
//        $item->deleted = true;
        $item->delete();
        return redirect('/'.app()->getLocale().'/admin/role/index')->with('status', __('admin.notifications.record_deleted'));
    }
}
