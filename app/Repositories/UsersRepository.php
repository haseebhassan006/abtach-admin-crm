<?php

namespace App\Repositories;

use App\Interfaces\UsersInterface;
use App\Models\Brand;
use App\Models\BrandHasManager;
use App\Models\BrandHasTeamLead;
use App\Models\Country;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserHasBrand;
use App\Models\UserHasSalesManager;
use App\Models\UserHasTeamLead;
use App\Models\UserHasUnit;
use App\Models\UserStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersRepository implements UsersInterface
{
    public function index($array)
    {
        $data['menu'] = 'users';
        $data['title'] = 'Users List';
        $data['roles'] = Role::all();
        $user = new User();
        $paginate = getPagination();
        if (isset($array['order']) && isset($array['by'])) {
            $user = $user->orderby($array['by'], $array['order']);
        } else {
            $user = $user->orderby('id', 'desc');
        }
        if (isset($array['limit']) && !empty($array['limit'])) {
            $paginate = (int) $array['limit'];
        }

        if (isset($array['role']) && !empty($array['role'])) {
            $user = $user->role($array['role']);
        }

        if (isset($array['keyword']) && !empty($array['keyword'])) {
            $keyword = $array['keyword'];
            $user = $user->where('first_name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('email', 'LIKE', '%' . $keyword . '%')
                ->orWhere('phone', 'LIKE', '%' . $keyword . '%');
        }
        $user = $user->where('id', '!=', Auth::user()->id);

        $data['records'] = $user->withCount('roles')->with('roles')->paginate($paginate);
        $data['check'] = checkpermission();

        return $data;
    }
    public function create()
    {
        $data['menu'] = 'users';
        $data['title'] = 'Create User';
        $data['roles'] = Role::all();
        return $data;
    }
    public function store($array)
    {
        $user = User::create([
             'name'=> $array['name'],
            'email' => $array['email'],
            'password' => Hash::make($array['password']),
            'password_string' => $array['password'],
        ]);
        if ($user->id) {
            $roles = $array['role_id'];
            $user->syncRoles($roles);
            return true;
        }
        return false;
    }
    public function  edit($id)
    {
        $data['menu'] = 'users';
        $data['title'] = 'Edit User';
        $data['edit'] = User::with(
            'roles',
        )->where('id', $id)->first();

        if (!empty($data['edit'])) {
            $brand = $data['edit']->brand;
            $data['roles'] = Role::all();
            return $data;
        }
        return false;
    }
    public function show($id)
    {
        $data['menu'] = "users";
        $data['title'] = "User Detail";
        $data['show'] = User::with(
            'roles',
        )->where('id', $id)->first();

        if (!empty($data['show'])) {
            return $data;
        }
        return false;
    }
    public function update($array, $id)
    {
        $edit = User::where('id', $id)->with('roles')->first();
        if (!empty($edit)) {
            if (!empty($edit->roles)) {
                $new_roles = isset($array['role_id']) && !empty($array['role_id']) ? $array['role_id'] : null;
                $old_roles = isset($edit->roles) && !empty($edit->roles) ? $edit->roles : null;
                // return [$new_roles, $old_roles];
                // removeUserOfThisRoleFromBrandAndUser($new_roles, $old_roles, $edit, $array['brand_id']);
            }
            if ($array['password']) {
                $password = Hash::make($array['password']);
                $password_string = $array['password'];
            } else {
                $password = $edit->password;
                $password_string = $edit->password_string;
            }
            $update = $edit->update([
                'name' => $array['name'],
                'email' => $array['email'],
                'password' => $password,
                'password_string' => $password_string,
            ]);
            if ($update = 1) {
                $user = User::where('id', $id)->first();
        
                $roles = $array['role_id'];
                $user->syncRoles($roles);

                return true;
            }
            return false;
        }
        return false;
    }
    public function delete($id)
    {
        $delete = User::with('roles')->where('id', $id)->first();

        if (!empty($delete)) {
            // $sync = deleteUserANdRemoveRoleFromBrandsAndUser($delete);
            $delete->roles()->detach();
            $delete->delete();
            return true;
        }
        return false;
    }
    // public function syncBrands($brand_id, $user_id)
    // {
    //     $old = UserHasBrand::where('user_id', $user_id)->get();
    //     if ($old->count() > 0) {

    //         if ($old->count() > 0) {
    //             foreach ($old as $value) {
    //                 $value->delete();
    //             }
    //         }
    //     }
    //     $assign = UserHasBrand::create([
    //         'brand_id' => $brand_id,
    //         'user_id' => $user_id,
    //     ]);


    //     return true;
    // }
  
    public function getRoles($user_id)
    {
        $user = User::where('id', $user_id)->first();
        if (!empty($user)) {
            $roles = $user->getRoleNames();
            return $roles;
        }
        return false;
    }
    public function directPermission($id)
    {
        $data['menu'] = 'users';
        $data['title'] = 'Assign Permisison directly to User';
        $data['all_permissions'] = Permission::all();
        $data['user']  = User::where('id', $id)->first();
        if (empty($data['user'])) {
            return false;
        }
        $data['permissions'] = $data['user']->getDirectPermissions();
        return $data;
    }
    public function storeDirectPermission($array)
    {

        $create = Permission::create([
            'name' => $array['name'],
        ]);
        if ($create->id) {
            $user = User::find($array['user_id']);
            if (!empty($user)) {
                $permission = $create->name;
                $user->givePermissionTo($permission);
                return true;
            }
            return false;
        }
    }
    public function deleteDirectPermission($array)
    {
        $user = User::where('id', $array['user_id'])->first();
        if (!empty($user)) {
            $permission = Permission::find($array['permission_id']);
            if (empty($permission)) {
                return redirect()->back()->with('error', 'Permission Not Found!');
            }
            $user->revokePermissionTo($permission);
            $permission->delete();
            return true;
        }
        return false;
    }
  

}
