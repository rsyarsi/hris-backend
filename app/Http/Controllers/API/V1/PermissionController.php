<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Role\RoleServiceInterface;
use App\Services\Permission\PermissionServiceInterface;
use App\Http\Requests\{AssignRoleRequest, PermissionRequest};

class PermissionController extends Controller
{
    use ResponseAPI;

    private $permissionService;
    private $roleService;

    public function __construct(PermissionServiceInterface $permissionService, RoleServiceInterface $roleService,)
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
            $permissions = $this->permissionService->index($perPage, $search);
            return $this->success('Permissions retrieved successfully', $permissions);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(PermissionRequest $request)
    {
        try {
            $data = $request->validated();
            $permission = $this->permissionService->store($data);
            return $this->success('Permission created successfully', $permission, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $permission = $this->permissionService->show($id);
            if (!$permission) {
                return $this->error('Permission not found', 404);
            }
            return $this->success('Permission retrieved successfully', $permission);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(PermissionRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $permission = $this->permissionService->update($id, $data);
            if (!$permission) {
                return $this->error('Permission not found', 404);
            }
            return $this->success('Permission updated successfully', $permission, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $permission = $this->permissionService->destroy($id);
            if (!$permission) {
                return $this->error('Permission not found', 404);
            }
            return $this->success('Permission deleted successfully, id : '.$permission->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function assignRole(AssignRoleRequest $request, $id)
    {
        try {
            $roles = $request->input('role');
            $permission = $this->permissionService->show($id);
            if (!$permission) {
                return $this->error('Permission not exists', 404);
            }
            foreach ($roles as $role) {
                if (!$permission->hasRole($role)) {
                    $permission->assignRole($role);
                } else {
                    return $this->error('Role Exists', 404);
                }
            }
            return $this->success('Role assigned in permission', []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function removeRole($permission, $role)
    {
        try {
            // Check if the permission exists
            $permission = $this->permissionService->show($permission);
            if (!$permission) {
                return $this->error('Permission not exists', 404);
            }

            // Check if the role exists
            $role = $this->roleService->show($role);
            if (!$role) {
                return $this->error('Role not exists', 404);
            }

            if ($permission->hasRole($role)) {
                $permission->removeRole($role);
                return $this->success('Role Removed', []);
            }
            return $this->error('Role not exists', 404);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
