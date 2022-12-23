<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Interfaces\RolesInterface;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    private $roleRepository;
    public function __construct(RolesInterface $roleRepository)
    {
        $this->roleRepository  = $roleRepository;
    }
    public function index(Request $request)
    {
       
        $data = $this->roleRepository->index($request->all());
        if (view()->exists('admin.roles.index')) {
            return view('admin.roles.index', $data);
        } else {
            abort(404);
        }
    }
    public function create()
    {
      
        $data = $this->roleRepository->create();
        if (view()->exists('admin.roles.create')) {
            return view('admin.roles.create', $data);
        } else {
            abort(404);
        }
    }
    public function store(RoleRequest $request)
    {
       
        $validate = $request->validated();
        $store  = $this->roleRepository->store($request->all());
        if ($store == true) {
            return redirect()->route('admin.roles.index')->with('success', 'Role Created!');
        }
        return back()->with('error', 'Role not Created!');
    }
    public function show($id)
    {
        $this->authorize("RolesView");
    }

    public function edit($id)
    {

        $this->authorize("RolesEdit");
        $data = $this->roleRepository->edit($id);
        if ($data == false) {
            return redirect()->route('admin.roles.index')->with('error', 'Role Not Found!');
        }
        if (view()->exists('admin.roles.edit')) {
            return view('admin.roles.edit', $data);
        } else {
            abort(404);
        }
    }

    public function update(RoleRequest $request, Role $role)
    {
        $this->authorize("RolesEdit");
        $validate = $request->validated();
        $update = $this->roleRepository->update($request->all(), $role);
        if ($update == true) {
            return redirect()->route('admin.roles.index')->with('success', 'Role Updated!');
        }
        return back()->with('error', 'Role not Updated!');
    }

    public function destroy($id)
    {
        $this->authorize("RolesDelete");
        $delete = $this->roleRepository->delete($id);
        if ($delete == true) {
            return redirect()->route('admin.roles.index')->with('success', 'Role Deleted!');
        }
        return redirect()->route('admin.roles.index')->with('error', 'Role not Deleted!');
    }
    public function getUsers(Request $request)
    {
        $role = Role::where('id', $request->role_id)->first();
        if (!empty($role)) {
            $users = $role->users;
            $user_data = [];
            foreach ($users as $index => $value) {
                $user_data[$index]['id'] =  ++$index;
                $user_data[$index]['url'] = route('admin.users.show', $value->id);
                $user_data[$index]['name'] = ucfirst($value->first_name ?? "") . " " . ucfirst($value->last_name ?? "");
            }

            return response()->json(['success' => true, 'users' => $user_data, 'message' => 'Users Fetched Successfuly!']);
        }
        return response()->json(['success' => false, 'message' => "User not found!"]);
    }
    public function  getPermissions(Request $request)
    {
        $role = Role::where('id', $request->role_id)->first();
        if (!empty($role)) {
            $permissions = $role->permissions;
            $permissions_data = [];
            foreach ($permissions as $index => $value) {
                $permissions_data[$index]['id'] =  ++$index;
                $permissions_data[$index]['name'] = ucfirst($value->name);
            }

            return response()->json(['success' => true, 'permissions' => $permissions_data, 'message' => 'Permissions Fetched Successfuly!']);
        }
        return response()->json(['success' => false, 'message' => "Permission not found!"]);
    }
}
