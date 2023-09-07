<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeOrganizationRequest;
use App\Services\EmployeeOrganization\EmployeeOrganizationServiceInterface;

class EmployeeOrganizationController extends Controller
{
    use ResponseAPI;

    private $employeeorganizationService;

    public function __construct(EmployeeOrganizationServiceInterface $employeeorganizationService)
    {
        $this->middleware('auth:api');
        $this->employeeorganizationService = $employeeorganizationService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeeorganizations = $this->employeeorganizationService->index($perPage, $search);
            return $this->success('Employee Organizations retrieved successfully', $employeeorganizations);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EmployeeOrganizationRequest $request)
    {
        try {
            $data = $request->validated();
            $employeeorganization = $this->employeeorganizationService->store($data);
            return $this->success('Employee Organization created successfully', $employeeorganization, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $employeeorganization = $this->employeeorganizationService->show($id);
            if (!$employeeorganization) {
                return $this->error('Employee Organization not found', 404);
            }
            return $this->success('Employee Organization retrieved successfully', $employeeorganization);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EmployeeOrganizationRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employeeorganization = $this->employeeorganizationService->update($id, $data);
            if (!$employeeorganization) {
                return $this->error('Employee Organization not found', 404);
            }
            return $this->success('Employee Organization updated successfully', $employeeorganization, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $employeeorganization = $this->employeeorganizationService->destroy($id);
            if (!$employeeorganization) {
                return $this->error('Employee Organization not found', 404);
            }
            return $this->success('Employee Organization deleted successfully, id : '.$employeeorganization->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
