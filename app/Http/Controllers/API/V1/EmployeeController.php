<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Services\Employee\EmployeeServiceInterface;

class EmployeeController extends Controller
{
    use ResponseAPI;

    private $employeeService;

    public function __construct(EmployeeServiceInterface $employeeService)
    {
        $this->middleware('auth:api');
        $this->employeeService = $employeeService;
    }

    public function index()
    {
        try {
            $employees = $this->employeeService->index();
            return $this->success('Employees retrieved successfully', $employees);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EmployeeRequest $request)
    {
        try {
            $data = $request->validated();
            $employee = $this->employeeService->store($data);
            return $this->success('Employee created successfully', $employee, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $employee = $this->employeeService->show($id);
            if (!$employee) {
                return $this->error('Employee not found', 404);
            }
            return $this->success('Employee retrieved successfully', $employee);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EmployeeRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employee = $this->employeeService->update($id, $data);
            if (!$employee) {
                return $this->error('Employee not found', 404);
            }
            return $this->success('Employee updated successfully', $employee, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $employee = $this->employeeService->destroy($id);
            if (!$employee) {
                return $this->error('Employee not found', 404);
            }
            return $this->success('Employee deleted successfully, id : '.$employee->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
