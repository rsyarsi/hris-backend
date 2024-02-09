<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use App\Exports\{LeaveExport, LeaveWhereStatusExport};
use Illuminate\Http\Request;
use App\Rules\DateSmallerThan;
use App\Http\Requests\LeaveRequest;
use App\Rules\UniqueLeaveDateRange;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
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
            $unit = $request->input('unit');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $leaves = $this->leaveService->index($perPage, $search, $period_1, $period_2, $unit);
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
            return response()->json([
                'message' => $leave['message'],
                'error' => $leave['error'],
                'code' => $leave['code'],
                'data' => $leave['data']
            ], $leave['code']);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function leaveCreateMobile(Request $request)
    {
        try {
            $rules = [
                'employee_id' => 'required|exists:employees,id',
                'leave_type_id' => 'required|exists:leave_types,id',
                'leave_status_id' => 'required|exists:leave_statuses,id',
                'from_date' => [
                    'required',
                    'date',
                    new DateSmallerThan('to_date'),
                ],
                'to_date' => 'required|date',
                'note' => 'required',
                'file' => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:5048',
            ];
            // Add a condition based on the value of Type
            $leaveTypeId = (int) $request->input('leave_type_id');
            // Add a condition based on the value of Type
            if ($leaveTypeId === 2) {
                $rules['file'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:5048';
            } else {
                $rules['file'] = 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:5048';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errorMessages = collect($validator->errors()->all())->implode(', '); // Collect and join errors with commas
                return response()->json([
                    'message' => 'Validation Error',
                    'success' => false,
                    'code' => 200, // Use a more appropriate HTTP status code
                    'data' => $errorMessages,
                ], 200);
            }

            $leave = $this->leaveService->leaveCreateMobile($request->all());
            return response()->json([
                'message' => $leave['message'],
                'success' => $leave['success'],
                'code' => $leave['code'],
                'data' => $leave['data']
            ], $leave['code']);
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
            return $this->success('Leave where employee retrieved successfully', $leaves);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function leaveHrdMobile(Request $request)
    {
        try {
            $leaves = $this->leaveService->leaveHrdMobile();
            return $this->success('Leave HRD retrieved successfully', $leaves);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function leaveSupervisorOrManager(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $leaveStatus = $request->input('leave_status');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $leaves = $this->leaveService->leaveSupervisorOrManager($perPage, $search, $leaveStatus, $startDate, $endDate);
            return $this->success('Leave where manager/supervisor/kabag retrieved successfully', $leaves);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function leaveSupervisorOrManagerMobile(Request $request)
    {
        try {
            $employeeId = $request->input('employee_id');
            $leaves = $this->leaveService->leaveSupervisorOrManagerMobile($employeeId);
            return $this->success('Leave where manager/supervisor/kabag mobile retrieved successfully', $leaves);
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
            $unit = $request->input('unit');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $leaves = $this->leaveService->leaveStatus($perPage, $search, $period_1, $period_2, $leaveStatus, $unit);
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

    public function updateStatusMobile(LeaveNewStatusRequest $request)
    {
        try {
            $leaveId = $request->input('leave_id');
            $leaveStatusId = $request->input('leave_status_id');
            $leave = $this->leaveService->updateStatusMobile($leaveId, $leaveStatusId);
            if (!$leave) {
                return $this->error('Leave not found', 404);
            }
            return $this->success('Leave status updated successfully', [$leave], 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function leaveSisa(Request $request)
    {
        try {
            $employeeId = $request->input('employee_id');
            $leave = $this->leaveService->leaveSisa($employeeId);
            if (!$leave) {
                return $this->error('Leave not found', 404);
            }
            return $this->success('Sisa Cuti retrieved successfully', $leave, 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function exportLeave(Request $request)
    {
        try {
            $period1 = $request->input('period_1');
            $period2 = $request->input('period_2');
            $nameFile = 'data-ketidakhadiran-'.date("Y-m-d", strtotime($period1)).'-'.date("Y-m-d", strtotime($period2)).'.xlsx';
            return Excel::download(new LeaveExport, $nameFile);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function exportLeaveWhereStatus(Request $request)
    {
        try {
            $period1 = $request->input('period_1');
            $period2 = $request->input('period_2');
            $status = $request->input('status');
            $nameFile = 'data-review-ketidakhadiran-'.date("Y-m-d", strtotime($period1)).'-'.date("Y-m-d", strtotime($period2)).'.xlsx';
            return Excel::download(new LeaveWhereStatusExport, $nameFile);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
