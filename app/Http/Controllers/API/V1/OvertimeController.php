<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Rules\DateSmallerThan;
use App\Http\Controllers\Controller;
use App\Rules\UniqueOvertimeDateRange;
use Illuminate\Support\Facades\Validator;
use App\Services\Overtime\OvertimeServiceInterface;
use App\Http\Requests\{OvertimeRequest, OvertimeNewStatusRequest};

class OvertimeController extends Controller
{
    use ResponseAPI;

    private $overtimeService;

    public function __construct(OvertimeServiceInterface $overtimeService)
    {
        $this->middleware('auth:api');
        $this->overtimeService = $overtimeService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $overtimes = $this->overtimeService->index($perPage, $search);
            return $this->success('Overtime retrieved successfully', $overtimes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(OvertimeRequest $request)
    {
        try {
            $data = $request->validated();
            $overtime = $this->overtimeService->store($data);
            return response()->json([
                'message' => $overtime['message'],
                'error' => $overtime['error'],
                'code' => $overtime['code'],
                'data' => $overtime['data']
            ], $overtime['code']);
            // return $this->success('Overtime created successfully', $overtime, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function overtimeCreateMobile(Request $request)
    {
        try {
            $rules = [
                'employee_id' => 'required|exists:employees,id',
                'task' => 'required|max:255',
                'note' => 'required|max:255',
                'overtime_status_id' => 'required|exists:overtime_statuses,id',
                'amount' => 'required|max:18',
                'type' => 'required|string|max:255',
                'from_date' => [
                                'required',
                                'date',
                                new DateSmallerThan('to_date')
                            ],
                'to_date' => 'required|date',
                'libur' => 'required|in:0,1',
            ];
            // if ($request->isMethod('post')) {
            //     $rules['from_date'][] = new UniqueOvertimeDateRange();
            // }
            $validator = Validator::make($request->all(), $rules);
            $errorMessages = collect($validator->errors()->all())->implode(', '); // Collect and join errors with commas
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation Error',
                    'success' => false,
                    'code' => 200, // Use a more appropriate HTTP status code
                    'data' => $errorMessages,
                ], 200);
            }
            $overtime = $this->overtimeService->overtimeCreateMobile($request->all());
            return response()->json([
                'message' => $overtime['message'],
                'success' => $overtime['success'],
                'code' => $overtime['code'],
                'data' => $overtime['data']
            ], $overtime['code']);
            // return $this->success('Overtime created successfully', $overtime, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $overtime = $this->overtimeService->show($id);
            if (!$overtime) {
                return $this->error('Overtime not found', 404);
            }
            return $this->success('Overtime retrieved successfully', $overtime);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(OvertimeRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $overtime = $this->overtimeService->update($id, $data);
            if (!$overtime) {
                return $this->error('Overtime not found', 404);
            }
            return $this->success('Overtime updated successfully', $overtime, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $overtime = $this->overtimeService->destroy($id);
            if (!$overtime) {
                return $this->error('Overtime not found', 404);
            }
            return $this->success('Overtime deleted successfully, id : '.$overtime->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function overtimeEmployee(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $overtimeStatus = $request->input('overtime_status');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $overtimes = $this->overtimeService->overtimeEmployee($perPage, $overtimeStatus, $startDate, $endDate);
            return $this->success('Overtime where employee retrieved successfully', $overtimes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function overtimeEmployeeMobile(Request $request)
    {
        $employeeId = $request->input('employment_id');
        $overtimes = $this->overtimeService->overtimeEmployeeMobile($employeeId);
        return $this->success('Overtime where employee retrieved successfully', $overtimes);
        try {
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function overtimeHrdMobile(Request $request)
    {
        try {
            $overtimes = $this->overtimeService->overtimeHrdMobile();
            return $this->success('Overtimes HRD retrieved successfully', $overtimes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function overtimeSupervisorOrManager(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $overtimeStatus = $request->input('overtime_status');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $overtimes = $this->overtimeService->overtimeSupervisorOrManager($perPage, $search, $overtimeStatus, $startDate, $endDate);
            return $this->success('Overtime where manager/supervisor/kabag login retrieved successfully', $overtimes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function overtimeSupervisorOrManagerMobile(Request $request)
    {
        try {
            $employeeId = $request->input('employee_id');
            $overtimes = $this->overtimeService->overtimeSupervisorOrManagerMobile($employeeId);
            return $this->success('Overtime where manager/supervisor/kabag mobile retrieved successfully', $overtimes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function overtimeStatus(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $overtimeStatus = $request->input('overtime_status');
            $unit = $request->input('unit');
            $overtimes = $this->overtimeService->overtimeStatus($perPage, $search, $overtimeStatus, $unit);
            return $this->success('Overtime where status retrieved successfully', $overtimes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function updateStatus(OvertimeNewStatusRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $overtime = $this->overtimeService->updateStatus($id, $data);
            if (!$overtime) {
                return $this->error('Overtime not found', 404);
            }
            return $this->success('Overtime status updated successfully', $overtime, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function updateStatusMobile(OvertimeNewStatusRequest $request)
    {
        try {
            $overtimeId = $request->input('overtime_id');
            $overtimeStatusId = $request->input('overtime_status_id');
            $overtime = $this->overtimeService->updateStatusMobile($overtimeId, $overtimeStatusId);
            if (!$overtime) {
                return $this->error('Overtime not found', 404);
            }
            return $this->success('Overtime status updated successfully', [$overtime], 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function overtimeEmployeeToday(Request $request)
    {
        $employeeId = $request->input('employment_id');
        $overtime = $this->overtimeService->overtimeEmployeeToday($employeeId);
        if ($overtime) {
            return response()->json([
                'message' => 'Overtime Karyawan hari ini berhasil di ambil!',
                'success' => 'true',
                'code' => 200,
                'data' => [$overtime],
            ]);
        } else {
            return response()->json([
                'message' => 'Karyawan tidak ditemukan atau tidak memiliki jadwal overtime hari ini.',
                'success' => 'false',
                'code' => 404,
                'data' => null,
            ]);
        }
        try {
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
