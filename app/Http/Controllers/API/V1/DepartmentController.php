<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Services\Department\DepartmentServiceInterface;

class DepartmentController extends Controller
{
    use ResponseAPI;

    private $departmentService;

    public function __construct(DepartmentServiceInterface $departmentService)
    {
        $this->middleware('auth:api');
        $this->departmentService = $departmentService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $departments = $this->departmentService->index($perPage, $search);
            return $this->success('Departments retrieved successfully', $departments);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(DepartmentRequest $request)
    {
        try {
            $data = $request->validated();
            $department = $this->departmentService->store($data);
            return $this->success('Department created successfully', $department, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $department = $this->departmentService->show($id);
            if (!$department) {
                return $this->error('Department not found', 404);
            }
            return $this->success('Department retrieved successfully', $department);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(DepartmentRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $department = $this->departmentService->update($id, $data);
            if (!$department) {
                return $this->error('Department not found', 404);
            }
            return $this->success('Department updated successfully', $department, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $department = $this->departmentService->destroy($id);
            if (!$department) {
                return $this->error('Department not found', 404);
            }
            return $this->success('Department deleted successfully, id : '.$department->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
