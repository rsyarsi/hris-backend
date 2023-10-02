<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeEducationRequest;
use App\Services\EmployeeEducation\EmployeeEducationServiceInterface;

class EmployeeEducationController extends Controller
{
    use ResponseAPI;

    private $employeeEducationService;

    public function __construct(EmployeeEducationServiceInterface $employeeEducationService)
    {
        $this->middleware('auth:api');
        $this->employeeEducationService = $employeeEducationService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeeeducations = $this->employeeEducationService->index($perPage, $search);
            return $this->success('Employee Educations retrieved successfully', $employeeeducations);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EmployeeEducationRequest $request)
    {
        try {
            $data = $request->validated();
            $employeeeducation = $this->employeeEducationService->store($data);
            return $this->success('Employee Education created successfully', $employeeeducation, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $employeeeducation = $this->employeeEducationService->show($id);
            if (!$employeeeducation) {
                return $this->error('Employee Education not found', 404);
            }
            return $this->success('Employee Education retrieved successfully', $employeeeducation);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EmployeeEducationRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employeeeducation = $this->employeeEducationService->update($id, $data);
            if (!$employeeeducation) {
                return $this->error('Employee Education not found', 404);
            }
            return $this->success('Employee Education updated successfully', $employeeeducation, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $employeeeducation = $this->employeeEducationService->destroy($id);
            if (!$employeeeducation) {
                return $this->error('Employee Education not found', 404);
            }
            return $this->success('Employee Education deleted successfully, id : '.$employeeeducation->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
