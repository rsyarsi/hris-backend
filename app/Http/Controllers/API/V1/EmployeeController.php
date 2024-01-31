<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Imports\EmployeeImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\UploadPhotoRequest;
use Illuminate\Support\Facades\Validator;
use App\Services\Employee\EmployeeServiceInterface;

class EmployeeController extends Controller
{
    use ResponseAPI;

    private $employeeService;

    public function __construct(EmployeeServiceInterface $employeeService)
    {
        $this->middleware('auth:api');
        $this->employeeService = $employeeService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $active = $request->input('active');
            $employees = $this->employeeService->index($perPage, $search, $active);
            return $this->success('Employees retrieved successfully', $employees);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EmployeeRequest $request)
    {
        try {
            $data = $request->validated();
            $employee = $this->employeeService->store($data);
            return $this->success('Employee created successfully', $employee, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function employeeUploadPhoto(UploadPhotoRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employee = $this->employeeService->employeeUploadPhoto($id, $data);
            return $this->success('Photo Uploaded successfully', $employee, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function employeeUploadPhotoMobile(Request $request)
    {
        try {
            $rules = [
                'employee_id' => 'required|exists:employees,id',
                'file' => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation Error',
                    'success' => false,
                    'code' => 200, // Use a more appropriate HTTP status code
                    'data' => 'Validation Error, Please check your data!',
                ], 200);
            }
            $employee = $this->employeeService->employeeUploadPhotoMobile($request->all());
            return response()->json([
                'message' => $employee['message'],
                'success' => $employee['success'],
                'code' => $employee['code'],
                'data' => $employee['data']
            ], $employee['code']);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $employee = $this->employeeService->show($id);
            if (!$employee) {
                return $this->error('Employee not found', 404);
            }
            return $this->success('Employee retrieved successfully', $employee);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EmployeeRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employee = $this->employeeService->update($id, $data);
            if (!$employee) {
                return $this->error('Employee not found', 404);
            }
            return $this->success('Employee updated successfully', $employee, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $employee = $this->employeeService->destroy($id);
            if (!$employee) {
                return $this->error('Employee not found', 404);
            }
            return $this->success('Employee deleted successfully, id : '.$employee->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function employeeNumberNull(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employees = $this->employeeService->employeeNumberNull($perPage, $search);
            return $this->success('Employees not have contract retrieved successfully', $employees);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function employeeEndContract(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employees = $this->employeeService->employeeEndContract($perPage, $search);
            return $this->success('Employees end contract retrieved successfully', $employees);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    function importEmployee()
    {
        try {
            Excel::import(new EmployeeImport, request()->file('file'));
            return $this->success('Employee imported successfully', [], 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function employeeActive(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employees = $this->employeeService->employeeActive($perPage, $search);
            return $this->success('Employees active retrieved successfully', $employees);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function employeeSubordinate(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employees = $this->employeeService->employeeSubordinate($perPage, $search);
            return $this->success('Employees Subordinate retrieved successfully', $employees);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function employeeSubordinateMobile(Request $request)
    {
        try {
            $employeeId = $request->input('employee_id');
            $employees = $this->employeeService->employeeSubordinateMobile($employeeId);
            return $this->success('Employees Subordinate mobile retrieved successfully', $employees);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function employeeNonShift(Request $request)
    {
        try {
            $employees = $this->employeeService->employeeNonShift();
            return $this->success('Employees Non Shift retrieved successfully', $employees);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function employeeHaveContractDetail(Request $request)
    {
        try {
            $employees = $this->employeeService->employeeHaveContractDetail();
            return $this->success('Employees Have Contract retrieved successfully', $employees);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function employeeResigned(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employees = $this->employeeService->employeeResigned($perPage, $search);
            return $this->success('Employees Resigned retrieved successfully', $employees);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
