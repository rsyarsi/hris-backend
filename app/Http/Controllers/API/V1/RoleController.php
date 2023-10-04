<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Requests\{RoleRequest, GivePermissionRequest};
use App\Http\Controllers\Controller;
use App\Services\Permission\PermissionServiceInterface;
use App\Services\Role\RoleServiceInterface;

class RoleController extends Controller
{
    use ResponseAPI;

    private $roleService;
    private $permissionService;

    public function __construct(RoleServiceInterface $roleService, PermissionServiceInterface $permissionService)
    {
        $this->middleware('auth:api');
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $roles = $this->roleService->index($perPage, $search);
            return $this->success('Roles retrieved successfully', $roles);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(RoleRequest $request)
    {
        try {
            $data = $request->validated();
            $role = $this->roleService->store($data);
            return $this->success('Role created successfully', $role, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $role = $this->roleService->show($id);
            if (!$role) {
                return $this->error('Role not found', 404);
            }
            return $this->success('Role retrieved successfully', $role);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(RoleRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $role = $this->roleService->update($id, $data);
            if (!$role) {
                return $this->error('Role not found', 404);
            }
            return $this->success('Role updated successfully', $role, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $role = $this->roleService->destroy($id);
            if (!$role) {
                return $this->error('Role not found', 404);
            }
            return $this->success('Role deleted successfully, id : '.$role->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function givePermission(GivePermissionRequest $request, $id)
    {
        try {
            $permissions = $request->input('permission');
            $role = $this->roleService->show($id);
            if (!$role) {
                return $this->error('Role not exists', 404);
            }
            foreach ($permissions as $permission) {
                if (!$role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                } else {
                    return $this->error('Permission Exists', 404);
                }
            }
            return $this->success('Permission added in role '.$role->name, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function revokePermission($role, $permission)
    {
        try {
            // Check if the role exists
            $role = $this->roleService->show($role);
            if (!$role) {
                return $this->error('Role not exists', 404);
            }

            // Check if the permission exists
            $permission = $this->permissionService->show($permission);
            if (!$permission) {
                return $this->error('Permission not exists', 404);
            }

            if ($role->hasPermissionTo($permission)) {
                $role->revokePermissionTo($permission);
                return $this->success('Permission revoke from role '.$role->name, []);
            }
            return $this->error('Permission not exists', 404);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
