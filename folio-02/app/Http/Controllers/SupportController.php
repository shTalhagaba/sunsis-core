<?php

/// this controller is no longer required, consider removing this class
namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Address;
use App\Models\License;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SupportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'is_support']);
    }

    public function home()
    {
        if(\Session::has('impersonate'))
        {
            return view('home');
        }

        return view('perspective.home');
    }

    public function view_users(Request $request)
    {
        // $users = User::all()->except(Auth::id());
        $filters = new \stdClass();
        $filters->sortBy = $request->has('sortBy') ? $request->sortBy : 'surname';
        $filters->orderBy = $request->has('orderBy') ? $request->orderBy : 'ASC';
        $filters->perPage = $request->has('perPage') ? $request->perPage : 20;
        $filters->user_type = $request->has('user_type') ? $request->user_type : null;
        $filters->firstnames = $request->has('firstnames') ? $request->firstnames : null;
        $filters->surname = $request->has('surname') ? $request->surname : null;
        $filters->email = $request->has('email') ? $request->email : null;

        $users = User::filter((array)$filters)->where('id', '!=', Auth::id())->orderBy($filters->sortBy, $filters->orderBy)->paginate($filters->perPage);

        return view('perspective.view_users', compact('users', 'filters'));
    }

    public function create_user()
    {
        $home_address = new Address();
        $work_address = new Address();

        return view('perspective.create_user', compact('home_address', 'work_address'));
    }

    public function view_permissions(Request $request)
    {
        $permissions = Permission::all();

        return view('perspective.view_permissions', compact('permissions'));
    }

    public function createPermission()
    {
        $permissions = Permission::all();

        return view('perspective.create-permission', compact('permissions'));
    }

    public function storePermission(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:5',
        ]);

        $permission = Permission::create($request->except('roles'));

        \Session::flash('alert-success', 'Permission: ' . $permission->name . ' added!');

        return redirect()->route('perspective.support.view_permissions');
    }

    public function editPermission($id)
    {
        $permission = Permission::findOrFail($id);

        return view('perspective.edit-permission', compact('permission'));
    }

    public function updatePermission(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|min:5',
        ]);

        $permission->update($request->except(['roles']));

        $permission->syncRoles($request->roles);

        \Session::flash('alert-success', 'Permission: ' . $permission->name . ' updated!');

        return redirect()->route('perspective.support.view_permissions');
    }

    public function destroyPermission($id)
    {
        $permission = Permission::findOrFail($id);

        if($permission->name == 'Administer roles & permissions')
        {
            \Session::flash('alert-danger', 'This permission cannot be deleted.');
            return redirect()->route('perspective.support.view_permissions');
        }

        Permission::destroy($id);

        \Session::flash('alert-success', 'Permission deleted!');

        return redirect()->route('perspective.support.view_permissions');
    }

    public function view_licenses()
    {
        $licenses = License::orderBy('id', 'DESC')->get();

        return view('licenses.index', compact('licenses'));
    }

    public function storeLicense(Request $request)
    {
        if(isset($request->id) && $request->id != '')
        {
            $license = License::findOrFail($request->id);
            $license->update($request->all());
            \Session::flash('alert-success', 'License record has been updated successfully.');
        }
        else
        {
            License::create($request->all() + ['created_by' => \Auth::user()->id]);
            \Session::flash('alert-success', 'License record has been added successfully.');
        }

        return back();
    }
}
