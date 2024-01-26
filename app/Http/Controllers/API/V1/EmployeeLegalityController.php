<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeeLegalityImport;
use App\Http\Requests\EmployeeLegalityRequest;
use App\Http\Requests\ImportEmployeeLegalityRequest;
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

    public function employeeLegalitiesEnded(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employees = $this->employeeLegalityService->employeeLegalitiesEnded($perPage, $search);
            return $this->success('Employees legality ended retrieved successfully', $employees);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function countEmployeeLegalitiesEnded(Request $request)
    {
        try {
            $employees = $this->employeeLegalityService->countEmployeeLegalitiesEnded();
            return $this->success('Employees count legality ended retrieved successfully', $employees);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function importEmployeeLegality(ImportEmployeeLegalityRequest $request)
    {
        try {
            $import = Excel::import(new EmployeeLegalityImport, request()->file('file'));
            return $this->success('Employee Legality imported successfully!', $import, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
