<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Interfaces\PermissionsInterface;
use Illuminate\Support\Facades\Validator;


class PermissionController extends Controller
{
    public function __construct(PermissionsInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
        // $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $this->authorize("PermissionsList");
        $data  = $this->permissionRepository->index($request->all());
        if (view()->exists('admin.permissions.index')) {
            return view('admin.permissions.index', $data);
        } else {
            abort(404);
        }
    }
    public function create()
    {
        $this->authorize("PermissionsCreate");
        $data = $this->permissionRepository->create();
        if (view()->exists('admin.permissions.create')) {
            return view('admin.permissions.create', $data);
        } else {
            abort(404);
        }
    }


    public function store(PermissionRequest $request)
    {
        $this->authorize("PermissionsCreate");
        $validate = $request->validated();
        try {
            $name = $request->name;
            $name = ucwords($name);
            $name = Str::plural($name);
            $perms = $request->permission;
            $roles = $request->role_id;
            $store = $this->permissionRepository->store($name, $perms, $roles);
            if ($store  == true) {
                return redirect()->route('admin.permissions.index')->with('success', 'Permission has been Created');
            }
            return redirect()->route('admin.permissions.index')->with('success', 'Something went wrong!');
        } catch (Exception $e) {
            return redirect()->route('admin.permissions.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize("PermissionsView");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize("PermissionsEdit");
        $data['menu'] = "permissions";
        $data['title'] = "Edit Permission";
        $data['edit'] = Permission::find($id);
        $data['roles'] = Role::all();
        if (empty($data['edit'])) {
            return redirect()->route('admin.permissions.index')->with('error', 'Permission Not Found!');
        }

        if (view()->exists('admin.permissions.edit')) {
            return view('admin.permissions.edit', $data);
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $this->authorize("PermissionsEdit");
        $msgs = [
            'role_id.required' => 'Please Select atleast 1 role',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:190|unique:permissions,name,' . $permission->id,
            'role_id' => 'sometimes|nullable|array',
            'role_id.*' => 'exists:roles,id',
        ],  $msgs);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {
            $update = $permission->update(['name' => $request->name]);
            if ($update = 1) {
                $roles = $request->role_id;
                $permission->syncRoles($roles);
                return redirect()->route('admin.permissions.index')->with('success', 'Permission Updated!');
            }
            return back()->with('error', 'Permission not Updated!');
        } catch (Exception $e) {
            return redirect()->route('admin.permissions.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize("PermissionsDelete");
        $permission = Permission::find($id);
        if (!empty($permission)) {
            $permission->delete();
            return redirect()->back()->with('success', 'Permission Deleted!');
        }
        return redirect()->back()->with('error', 'Permission not Deleted!');
    }

    public function view_roles_of_permissions(Request $request)
    {
        $permission = Permission::where('id', $request->permission_id)->first();
        if (!empty($permission)) {
            $roles = $permission->roles;
            $roles_data = [];
            foreach ($roles as $index => $value) {
                $roles_data[$index]['id'] =  $value->id;
                $roles_data[$index]['name'] = ucfirst($value->name);
            }

            return response()->json(['success' => true, 'roles' => $roles_data, 'message' => 'Roles Fetched Successfuly!']);
        }
        return response()->json(['success' => false, 'message' => "Roles not found!"]);
    }
}
