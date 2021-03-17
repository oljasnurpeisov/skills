<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;

use Illuminate\Http\Request;

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
        $main_roles = ['admin', 'user', 'author', 'student', 'tech_support'];

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

        return redirect('/' . app()->getLocale() . '/admin/role/' . $item->id)->with('status', __('admin.notifications.record_stored'));
    }

    public function edit($lang, Role $item)
    {
        $main_roles = ['admin', 'user', 'author', 'student', 'tech_support'];

        $permissions = Permission::orderBy('id', 'asc')->get();
        return view('admin.v2.pages.roles.edit', [
            'item' => $item,
            'permissions' => $permissions,
            'main_roles' => $main_roles
        ]);
    }

    public function update(Request $request, $lang, Role $item)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:255|unique:roles,name,' . $item->id,
        ]);

        $item->name = $request->name;
        $item->description = $request->description;
        $item->save();

        $item->permissions()->sync($request->get('permissions', []));

        return redirect('/' . app()->getLocale() . '/admin/role/' . $item->id)->with('status', __('admin.notifications.record_updated'));
    }

    public function delete($lang, Role $item)
    {
        $main_roles = ['admin', 'user', 'author', 'student', 'tech_support'];
        if (in_array($item->slug, $main_roles)) {
            return back('/' . app()->getLocale() . '/admin/role/index')->with('status', 'Нельзя удалить системную запись');
        }

        $item->delete();
        return redirect('/' . app()->getLocale() . '/admin/role/index')->with('status', __('admin.notifications.record_deleted'));
    }
}
