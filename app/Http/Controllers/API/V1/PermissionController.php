<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Services\Permission\PermissionServiceInterface;

class PermissionController extends Controller
{
    use ResponseAPI;

    private $permissionService;

    public function __construct(PermissionServiceInterface $permissionService)
    {
        $this->middleware('auth:api');
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
}
