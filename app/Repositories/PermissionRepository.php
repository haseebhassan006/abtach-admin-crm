<?php

namespace App\Repositories;

use App\Interfaces\PermissionsInterface;
use App\Models\DummyPermissions;
use App\Models\Permission;
use App\Models\Role;
use Str;

class PermissionRepository implements PermissionsInterface
{
    public function index($array)
    {
        $data['menu'] = "permissions";
        $data['title'] = "Permissions List";
        $paginate  = getPagination();
        $permission = new Permission();
        if (isset($array['order']) && isset($array['by'])) {
            $permission = $permission->orderby($array['by'], $array['order']);
        } else {
            $permission = $permission->orderby('created_at', 'desc');
        }
        if (isset($array['limit']) && !empty($array['limit'])) {
            $paginate = (int) $array['limit'];
        }

        if (isset($array['keyword']) && !empty($array['keyword'])) {
            $keyword = $array['keyword'];
            $permission = $permission->where('name', 'LIKE', '%' . $keyword . '%');
        }
        $permissions = Permission::all()->groupBy(function ($item) {
            return $item->guard_name;
        });
        // dd($permissions);
        $data['records'] = $permission->withCount('roles', 'users')->paginate($paginate);
        $data['check'] = checkpermission();
        return $data;
    }
    public function create()
    {
        $data['menu'] = "permissions";
        $data['title'] = "Create Permission";
        $data['roles'] = Role::withCount(['users', 'permissions'])->paginate(10);
        $data['dummyPermissions'] = DummyPermissions::all();
        return $data;
    }
    public function store($name, $perms, $roles)
    {
        if (!empty($perms) && count($perms) > 0) {
            foreach ($perms as $value) {
                $value = $name . $value;
                $value = str_replace(" ", "", $value);
                $permission = Permission::create([
                    'name' => $value,
                ]);
                if (isset($roles) && !empty($roles)) {

                    $permission->syncRoles($roles);
                }
            }
        } else {
            $permission = Permission::create([
                'name' => $name,
            ]);
            if (isset($roles) && !empty($roles)) {
                $permission->syncRoles($roles);
            }
        }
        return true;
    }
}
