<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;

class DepartmentController extends Controller
{
    use ResponseAPI;

    private $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function index()
    {
        try {
            $departments = $this->departmentRepository->index();
            return $this->success('Departments retrieved successfully', $departments);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(DepartmentRequest $request)
    {
        try {
            $data = $request->validated();
            $department = $this->departmentRepository->store($data);
            return $this->success('Department created successfully', $department, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $department = $this->departmentRepository->show($id);
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
            $department = $this->departmentRepository->update($id, $data);
            if (!$department) {
                return $this->error('Department not found', 404);
            }
            return $this->success('Department updated successfully', $department);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->departmentRepository->destroy($id);
            if (!$result) {
                return $this->error('Department not found', 404);
            }
            return $this->success('Department deleted successfully', null, 204);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
