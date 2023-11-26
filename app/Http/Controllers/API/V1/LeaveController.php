<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Requests\LeaveRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveNewStatusRequest;
use App\Services\Leave\LeaveServiceInterface;

class LeaveController extends Controller
{
    use ResponseAPI;

    private $leaveService;

    public function __construct(LeaveServiceInterface $leaveService)
    {
        $this->middleware('auth:api');
        $this->leaveService = $leaveService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $leaves = $this->leaveService->index($perPage, $search);
            return $this->success('Leave retrieved successfully', $leaves);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(LeaveRequest $request)
    {
        try {
            $data = $request->validated();
            $leave = $this->leaveService->store($data);
            return $this->success('Leave created successfully', $leave, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $leave = $this->leaveService->show($id);
            if (!$leave) {
                return $this->error('Leave not found', 404);
            }
            return $this->success('Leave retrieved successfully', $leave);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(LeaveRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $leave = $this->leaveService->update($id, $data);
            if (!$leave) {
                return $this->error('Leave not found', 404);
            }
            return $this->success('Leave updated successfully', $leave, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $leave = $this->leaveService->destroy($id);
            if (!$leave) {
                return $this->error('Leave not found', 404);
            }
            return $this->success('Leave deleted successfully, id : '.$leave->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function leaveEmployee(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $leaveStatus = $request->input('leave_status');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $leaves = $this->leaveService->leaveEmployee($perPage, $leaveStatus, $startDate, $endDate);
            return $this->success('Leave where status retrieved successfully', $leaves);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function leaveEmployeeMobile(Request $request)
    {
        try {
            $employeeId = $request->input('employment_id');
            $leaves = $this->leaveService->leaveEmployeeMobile($employeeId);
            return $this->success('Leave where status retrieved successfully', $leaves);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function leaveSupervisorOrManager(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $leaveStatus = $request->input('leave_status');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $leaves = $this->leaveService->leaveSupervisorOrManager($perPage, $leaveStatus, $startDate, $endDate);
            return $this->success('Leave where manager/supervisor retrieved successfully', $leaves);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function leaveStatus(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $leaveStatus = $request->input('leave_status');
            $leaves = $this->leaveService->leaveStatus($perPage, $search, $leaveStatus);
            return $this->success('Leave where status retrieved successfully', $leaves);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function updateStatus(LeaveNewStatusRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $leave = $this->leaveService->updateStatus($id, $data);
            if (!$leave) {
                return $this->error('Leave not found', 404);
            }
            return $this->success('Leave status updated successfully', $leave, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
