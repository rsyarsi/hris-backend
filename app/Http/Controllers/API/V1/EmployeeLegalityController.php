<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeLegalityRequest;
use App\Services\EmployeeLegality\EmployeeLegalityServiceInterface;

class EmployeeLegalityController extends Controller
{
    use ResponseAPI;

    private $employeeLegalityService;

    public function __construct(EmployeeLegalityServiceInterface $employeeLegalityService)
    {
        $this->middleware('auth:api');
        $this->employeeLegalityService = $employeeLegalityService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeelegalitys = $this->employeeLegalityService->index($perPage, $search);
            return $this->success('Employee Legalitys retrieved successfully', $employeelegalitys);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EmployeeLegalityRequest $request)
    {
        // return $request->all();
        try {
            $data = $request->validated();
            $employeelegality = $this->employeeLegalityService->store($data);
            return $this->success('Employee Legality created successfully', $employeelegality, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $employeelegality = $this->employeeLegalityService->show($id);
            if (!$employeelegality) {
                return $this->error('Employee Legality not found', 404);
            }
            return $this->success('Employee Legality retrieved successfully', $employeelegality);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EmployeeLegalityRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employeelegality = $this->employeeLegalityService->update($id, $data);
            if (!$employeelegality) {
                return $this->error('Employee Legality not found', 404);
            }
            return $this->success('Employee Legality updated successfully', $employeelegality, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $employeelegality = $this->employeeLegalityService->destroy($id);
            if (!$employeelegality) {
                return $this->error('Employee Legality not found', 404);
            }
            return $this->success('Employee Legality deleted successfully, id : '.$employeelegality->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
