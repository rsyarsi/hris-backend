<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeePositionHistoryRequest;
use App\Services\EmployeePositionHistory\EmployeePositionHistoryServiceInterface;

class EmployeePositionHistoryController extends Controller
{
    use ResponseAPI;

    private $employeePositionHistoryService;

    public function __construct(EmployeePositionHistoryServiceInterface $employeePositionHistoryService)
    {
        $this->middleware('auth:api');
        $this->employeePositionHistoryService = $employeePositionHistoryService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeepositionhistorys = $this->employeePositionHistoryService->index($perPage, $search);
            return $this->success('Employee Position History retrieved successfully', $employeepositionhistorys);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EmployeePositionHistoryRequest $request)
    {
        try {
            $data = $request->validated();
            $employeepositionhistory = $this->employeePositionHistoryService->store($data);
            return $this->success('Employee Position History created successfully', $employeepositionhistory, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $employeepositionhistory = $this->employeePositionHistoryService->show($id);
            if (!$employeepositionhistory) {
                return $this->error('Employee Position History not found', 404);
            }
            return $this->success('Employee Position History retrieved successfully', $employeepositionhistory);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EmployeePositionHistoryRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employeepositionhistory = $this->employeePositionHistoryService->update($id, $data);
            if (!$employeepositionhistory) {
                return $this->error('Employee Position History not found', 404);
            }
            return $this->success('Employee Position History updated successfully', $employeepositionhistory, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $employeepositionhistory = $this->employeePositionHistoryService->destroy($id);
            if (!$employeepositionhistory) {
                return $this->error('Employee Position History not found', 404);
            }
            return $this->success('Employee Position History deleted successfully, id : '.$employeepositionhistory->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
