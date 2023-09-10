<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeExperienceRequest;
use App\Services\EmployeeExperience\EmployeeExperienceServiceInterface;

class EmployeeExperienceController extends Controller
{
    use ResponseAPI;

    private $employeeExperienceService;

    public function __construct(EmployeeExperienceServiceInterface $employeeExperienceService)
    {
        $this->middleware('auth:api');
        $this->employeeExperienceService = $employeeExperienceService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeeexperiences = $this->employeeExperienceService->index($perPage, $search);
            return $this->success('Employee Experiences retrieved successfully', $employeeexperiences);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EmployeeExperienceRequest $request)
    {
        try {
            $data = $request->validated();
            $employeeexperience = $this->employeeExperienceService->store($data);
            return $this->success('Employee Experience created successfully', $employeeexperience, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $employeeexperience = $this->employeeExperienceService->show($id);
            if (!$employeeexperience) {
                return $this->error('Employee Experience not found', 404);
            }
            return $this->success('Employee Experience retrieved successfully', $employeeexperience);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EmployeeExperienceRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employeeexperience = $this->employeeExperienceService->update($id, $data);
            if (!$employeeexperience) {
                return $this->error('Employee Experience not found', 404);
            }
            return $this->success('Employee Experience updated successfully', $employeeexperience, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $employeeexperience = $this->employeeExperienceService->destroy($id);
            if (!$employeeexperience) {
                return $this->error('Employee Experience not found', 404);
            }
            return $this->success('Employee Experience deleted successfully, id : '.$employeeexperience->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
