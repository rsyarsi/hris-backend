<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeFamilyRequest;
use App\Services\EmployeeFamily\EmployeeFamilyServiceInterface;

class EmployeeFamilyController extends Controller
{
    use ResponseAPI;

    private $employeeFamilyService;

    public function __construct(EmployeeFamilyServiceInterface $employeeFamilyService)
    {
        $this->middleware('auth:api');
        $this->employeeFamilyService = $employeeFamilyService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeefamilys = $this->employeeFamilyService->index($perPage, $search);
            return $this->success('Employee Family retrieved successfully', $employeefamilys);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EmployeeFamilyRequest $request)
    {
        try {
            $data = $request->validated();
            $employeefamily = $this->employeeFamilyService->store($data);
            return $this->success('Employee Family created successfully', $employeefamily, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $employeefamily = $this->employeeFamilyService->show($id);
            if (!$employeefamily) {
                return $this->error('Employee Family not found', 404);
            }
            return $this->success('Employee Family retrieved successfully', $employeefamily);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EmployeeFamilyRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employeefamily = $this->employeeFamilyService->update($id, $data);
            if (!$employeefamily) {
                return $this->error('Employee Family not found', 404);
            }
            return $this->success('Employee Family updated successfully', $employeefamily, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $employeefamily = $this->employeeFamilyService->destroy($id);
            if (!$employeefamily) {
                return $this->error('Employee Family not found', 404);
            }
            return $this->success('Employee Family deleted successfully, id : '.$employeefamily->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
