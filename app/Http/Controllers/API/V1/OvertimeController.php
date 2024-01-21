<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\{OvertimeRequest, OvertimeNewStatusRequest};
use App\Services\Overtime\OvertimeServiceInterface;

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
            $overtimes = $this->overtimeService->overtimeStatus($perPage, $search, $overtimeStatus);
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
            return $this->success('Overtime status updated successfully', [$overtime], 201);
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
