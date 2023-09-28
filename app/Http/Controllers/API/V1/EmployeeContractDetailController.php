<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeContractDetailRequest;
use App\Services\EmployeeContractDetail\EmployeeContractDetailServiceInterface;

class EmployeeContractDetailController extends Controller
{
    use ResponseAPI;

    private $employeeContractDetailService;

    public function __construct(EmployeeContractDetailServiceInterface $employeeContractDetailService)
    {
        $this->middleware('auth:api');
        $this->employeeContractDetailService = $employeeContractDetailService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeeContractDetails = $this->employeeContractDetailService->index($perPage, $search);
            return $this->success('Employee Contract Details retrieved successfully', $employeeContractDetails);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EmployeeContractDetailRequest $request)
    {
        try {
            $data = $request->validated();
            $employeeContractDetail = $this->employeeContractDetailService->store($data);
            return $this->success('Employee Contract Detail created successfully', $employeeContractDetail, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $employeeContractDetail = $this->employeeContractDetailService->show($id);
            if (!$employeeContractDetail) {
                return $this->error('Employee Contract Detail not found', 404);
            }
            return $this->success('Employee Contract Detail retrieved successfully', $employeeContractDetail);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EmployeeContractDetailRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employeeContractDetail = $this->employeeContractDetailService->update($id, $data);
            if (!$employeeContractDetail) {
                return $this->error('Employee Contract Detail not found', 404);
            }
            return $this->success('Employee Contract Detail updated successfully', $employeeContractDetail, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $employeeContractDetail = $this->employeeContractDetailService->destroy($id);
            if (!$employeeContractDetail) {
                return $this->error('Employee Contract Detail not found', 404);
            }
            return $this->success('Employee Contract Detail deleted successfully, id : '.$employeeContractDetail->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
