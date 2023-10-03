<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;
use App\Services\Role\RoleServiceInterface;

class RoleController extends Controller
{
    use ResponseAPI;

    private $roleService;

    public function __construct(RoleServiceInterface $roleService)
    {
        $this->middleware('auth:api');
        $this->roleService = $roleService;
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
}
