<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeContractRequest;
use App\Services\EmployeeContract\EmployeeContractServiceInterface;

class EmployeeContractController extends Controller
{
    use ResponseAPI;

    private $employeeContractService;

    public function __construct(EmployeeContractServiceInterface $employeeContractService)
    {
        $this->middleware('auth:api');
        $this->employeeContractService = $employeeContractService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeeContracts = $this->employeeContractService->index($perPage, $search);
            return $this->success('Employee Contracts retrieved successfully', $employeeContracts);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EmployeeContractRequest $request)
    {
        try {
            $data = $request->validated();
            $employeeContract = $this->employeeContractService->store($data);
            return $this->success('Employee Contract created successfully', $employeeContract, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $employeeContract = $this->employeeContractService->show($id);
            if (!$employeeContract) {
                return $this->error('Employee Contract not found', 404);
            }
            return $this->success('Employee Contract retrieved successfully', $employeeContract);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EmployeeContractRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employeeContract = $this->employeeContractService->update($id, $data);
            if (!$employeeContract) {
                return $this->error('Employee Contract not found', 404);
            }
            return $this->success('Employee Contract updated successfully', $employeeContract, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $employeeContract = $this->employeeContractService->destroy($id);
            if (!$employeeContract) {
                return $this->error('Employee Contract not found', 404);
            }
            return $this->success('Employee Contract deleted successfully, id : '.$employeeContract->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
