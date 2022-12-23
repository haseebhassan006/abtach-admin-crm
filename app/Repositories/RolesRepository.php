<?php

namespace App\Repositories;

use App\Interfaces\RolesInterface;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class RolesRepository implements RolesInterface
{
    public function index($array)
    {
        $data['menu'] = "roles";
        $data['title'] = "Roles List";
        $data['check'] = checkpermission();
        $paginate = getPagination();
        $role = new Role();
        if (isset($array['order']) && isset($array['by'])) {
            $role = $role->orderby($array['by'], $array['order']);
        } else {
            $role = $role->orderby('created_at', 'desc');
        }
        if (isset($array['limit']) && !empty($array['limit'])) {
            $paginate = (int) $array['limit'];
        }

        if (isset($array['keyword']) && !empty($array['keyword'])) {
            $keyword = $array['keyword'];
            $role = $role->where('name', 'LIKE', '%' . $keyword . '%');
        }
        $data['records'] = $role->withCount(['users', 'permissions'])->paginate($paginate);
        return $data;
    }
    public function create()
    {
        $data['menu'] = "roles";
        $data['title'] = "Create Role";
        $data['users'] = User::all();
        $data['permissions'] = Permission::all();
        return $data;
    }
    public function store($array)
    {
        $default_guard = config('auth.defaults.guard');
        $role = Role::create(['name' => $array['name'], 'guard_name' => $default_guard]);
        if ($role->id) {
            if (isset($array['user_id']) && !empty($array['user_id'])) {
                $users = $array['user_id'];
                $role->users()->attach($users);
            }
            if (isset($array['permission_id']) && !empty($array['permission_id'])) {
                $permissions = $array['permission_id'];
                $role->syncPermissions($permissions);
            }
            return true;
        }
        return false;
    }
    public function edit($id)
    {
        $data['menu'] = "roles";
        $data['title'] = "Edit Role";
        $data['edit'] = Role::find($id);
        $data['users'] = User::all();
        $data['permissions'] = Permission::all();
        if (!empty($data['edit'])) {
            return $data;
        }
        return false;
    }
    public function update($array, $role)
    {
        $update = $role->update(['name' => $array['name']]);

        if ($update = 1) {
            $users = $array['user_id'];
            $role->users()->sync($users);
            if (isset($array['permission_id']) && !empty($array['permission_id'])) {
                $permissions = $array['permission_id'];
                $role->syncPermissions($permissions);
            } else {
                foreach ($role->permissions as $index => $value) {
                    $role->revokePermissionTo($value);
                }
            }
            return true;
        }
        return false;
    }
    public function delete($id)
    {
        $role = Role::find($id);
        if (!empty($role)) {
            $role->delete();
            return true;
        }
        return false;
    }
}
