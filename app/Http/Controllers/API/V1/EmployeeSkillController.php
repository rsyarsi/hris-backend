<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeSkillRequest;
use App\Services\EmployeeSkill\EmployeeSkillServiceInterface;

class EmployeeSkillController extends Controller
{
    use ResponseAPI;

    private $employeeSkillService;

    public function __construct(EmployeeSkillServiceInterface $employeeSkillService)
    {
        $this->middleware('auth:api');
        $this->employeeSkillService = $employeeSkillService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeeskills = $this->employeeSkillService->index($perPage, $search);
            return $this->success('Employee Skills retrieved successfully', $employeeskills);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EmployeeSkillRequest $request)
    {
        try {
            $data = $request->validated();
            $employeeskill = $this->employeeSkillService->store($data);
            return $this->success('Employee Skill created successfully', $employeeskill, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $employeeskill = $this->employeeSkillService->show($id);
            if (!$employeeskill) {
                return $this->error('Employee Skill not found', 404);
            }
            return $this->success('Employee Skill retrieved successfully', $employeeskill);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EmployeeSkillRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employeeskill = $this->employeeSkillService->update($id, $data);
            if (!$employeeskill) {
                return $this->error('Employee Skill not found', 404);
            }
            return $this->success('Employee Skill updated successfully', $employeeskill, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $employeeskill = $this->employeeSkillService->destroy($id);
            if (!$employeeskill) {
                return $this->error('Employee Skill not found', 404);
            }
            return $this->success('Employee Skill deleted successfully, id : '.$employeeskill->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
