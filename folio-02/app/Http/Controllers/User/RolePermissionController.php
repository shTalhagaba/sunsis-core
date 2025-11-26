<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use App\Models\Lookups\UserTypeLookup;
use Session;

class RolePermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);//isAdmin middleware lets only users with a specific permission to access these resources
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	if(\Auth::user()->isStudent())
        {
            abort(403, '403. Unauthorised');
        }
	if(\Auth::user()->getOriginal('user_type') != UserTypeLookup::TYPE_ADMIN)
        {
            abort(403, 'Forbidden.');
        }

        $roles = Role::all();

        $permissions = Permission::all();

        return view('admin.rp.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createRole()
    {
	if(\Auth::user()->isStudent())
        {
            abort(403, '403. Unauthorised');
        }
	if(\Auth::user()->getOriginal('user_type') != UserTypeLookup::TYPE_ADMIN)
        {
            abort(403, 'Forbidden.');
        }

        $permissions = Permission::all();

        $roles = Role::all();

        return view('admin.rp.create-role', compact('permissions', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRole(Request $request)
    {
	if(\Auth::user()->isStudent())
        {
            abort(403, '403. Unauthorised');
        }
	if(\Auth::user()->getOriginal('user_type') != UserTypeLookup::TYPE_ADMIN)
        {
            abort(403, 'Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required|unique:roles|min:5|max:100',
            'permissions' => 'required',
        ]);

        $role = Role::create($request->except('permissions'));

        foreach($request->permissions AS $permission_id)
        {
            $permission = Permission::findOrFail($permission_id);
            $role->givePermissionTo($permission);
        }

        \Session::flash('message', 'Role: ' . $role->name . ' added!');
        \Session::flash('alert-class', 'alert-success');

        return redirect()->route('rp.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editRole($id)
    {
	if(\Auth::user()->isStudent())
        {
            abort(403, '403. Unauthorised');
        }
	if(\Auth::user()->getOriginal('user_type') != UserTypeLookup::TYPE_ADMIN)
        {
            abort(403, 'Forbidden.');
        }

        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $roles = Role::where('id', '!=', $role->id)->get();

        return view('admin.rp.edit-role', compact('role', 'permissions', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRole(Request $request, $id)
    {
	if(\Auth::user()->isStudent())
        {
            abort(403, '403. Unauthorised');
        }
	if(\Auth::user()->getOriginal('user_type') != UserTypeLookup::TYPE_ADMIN)
        {
            abort(403, 'Forbidden.');
        }

        $role = Role::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|min:5|unique:roles,name,'.$id,
            'permissions' => 'required',
        ]);

        $role->update($request->except(['permissions']));

        $role->syncPermissions($request->permissions);

        \Session::flash('alert-success', 'Role: ' . $role->name . ' updated!');

        return redirect()->route('rp.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyRole($id)
    {
	if(\Auth::user()->isStudent())
        {
            abort(403, '403. Unauthorised');
        }
	if(\Auth::user()->getOriginal('user_type') != UserTypeLookup::TYPE_ADMIN)
        {
            abort(403, 'Forbidden.');
        }

        Role::destroy($id);

        \Session::flash('alert-success', 'Role deleted!');

	return redirect()->route('rp.index');
    }


}
