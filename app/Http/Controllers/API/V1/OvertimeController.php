<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\DateSmallerThan;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Rules\UniqueOvertimeDateRange;
use Illuminate\Support\Facades\Validator;
use App\Models\{Department, Unit, Employee};
use App\Services\Overtime\OvertimeServiceInterface;
use App\Http\Requests\{OvertimeRequest, OvertimeNewStatusRequest};
use App\Exports\{OvertimeExport, OvertimeWhereStatusExport, OvertimeWhereEmployeeExport, OvertimeWhereUnitExport, OvertimeWhereDepartmentExport};

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
            $unit = $request->input('unit');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $overtimes = $this->overtimeService->index($perPage, $search, $period_1, $period_2, $unit);
            return $this->success('Overtime retrieved successfully', $overtimes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function overtimeEmployeeRekap(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $employeeId = $request->input('employee_id');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $overtimes = $this->overtimeService->overtimeEmployeeRekap($perPage, $employeeId, $period_1, $period_2);
            return $this->success('Rekap overtime employee retrieved successfully', $overtimes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function overtimeUnitRekap(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $unit = $request->input('unit');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $overtimes = $this->overtimeService->overtimeUnitRekap($perPage, $search, $period_1, $period_2, $unit);
            return $this->success('Rekap overtime unit retrieved successfully', $overtimes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function overtimedepartmentRekap(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $department = $request->input('department');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $overtimes = $this->overtimeService->overtimedepartmentRekap($perPage, $search, $period_1, $period_2, $department);
            return $this->success('Rekap overtime department retrieved successfully', $overtimes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            $employee = Employee::find($request->employee_id);
            $unitId = $employee->unit_id ?? null;
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
            if ($request->type === 'ONCALL' && $unitId !== 19 || $request->type === 'CITO' && $unitId !== 19) {
                return response()->json([
                    'message' => 'Validation Error!',
                    'error' => false,
                    'code' => 422, // Use a more appropriate HTTP status code
                    'data' => [
                        'type' => [
                            'Type ONCALL/CITO hanya untuk unit HEMODIALISA.'
                        ]
                    ],
                ], 422);
            }
            // if ($request->isMethod('post')) {
            //     $rules['from_date'][] = new UniqueOvertimeDateRange();
            // }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation Error',
                    'error' => false,
                    'code' => 422, // Use a more appropriate HTTP status code
                    'data' => $validator->errors(),
                ], 422);
            }
            $overtime = $this->overtimeService->store($request->all());
            return response()->json([
                'message' => $overtime['message'],
                'error' => $overtime['error'],
                'code' => $overtime['code'],
                'data' => $overtime['data']
            ], $overtime['code']);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function overtimeCreateMobile(Request $request)
    {
        try {
            $employee = Employee::find($request->employee_id);
            $unitId = $employee->unit_id ?? null;
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
            if ($request->type === 'ONCALL' && $unitId !== 19 || $request->type === 'CITO' && $unitId !== 19) {
                return response()->json([
                    'message' => 'Type ONCALL/CITO hanya untuk unit HEMODIALISA.',
                    'success' => false,
                    'code' => 200, // Use a more appropriate HTTP status code
                    'data' => 'Type ONCALL/CITO hanya untuk unit HEMODIALISA.',
                ], 200);
            }
            // if ($request->isMethod('post')) {
            //     $rules['from_date'][] = new UniqueOvertimeDateRange();
            // }
            $validator = Validator::make($request->all(), $rules);
            $errorMessages = collect($validator->errors()->all())->implode(', '); // Collect and join errors with commas
            if ($validator->fails()) {
                return response()->json([
                    'message' => $errorMessages,
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
        try {
            $employeeId = $request->input('employment_id');
            $overtimes = $this->overtimeService->overtimeEmployeeMobile($employeeId);
            return $this->success('Overtime where employee retrieved successfully', $overtimes);
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
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $overtimes = $this->overtimeService->overtimeStatus($perPage, $search, $period_1, $period_2, $overtimeStatus, $unit);
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
            return $overtime;
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
        try {
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
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function exportOvertime(Request $request)
    {
        try {
            $period1 = $request->input('period_1');
            $period2 = $request->input('period_2');
            $nameFile = 'data-overtime-'.date("Y-m-d", strtotime($period1)).'-'.date("Y-m-d", strtotime($period2)).'.xlsx';
            return Excel::download(new OvertimeExport, $nameFile);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function exportOvertimeWhereStatus(Request $request)
    {
        try {
            $period1 = $request->input('period_1');
            $period2 = $request->input('period_2');
            $status = $request->input('status');
            $nameFile = 'data-review-overtime-'.date("Y-m-d", strtotime($period1)).'-'.date("Y-m-d", strtotime($period2)).'.xlsx';
            return Excel::download(new OvertimeWhereStatusExport, $nameFile);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function exportOvertimeEmployee(Request $request)
    {
        try {
            $employeeId = $request->input('employee_id');
            $period1 = $request->input('period_1');
            $period2 = $request->input('period_2');
            $employee = Employee::where('id', $employeeId)->first(['id', 'name', 'employment_number']);
            if (!$employee) {
                return $this->error('Employee Not Found', 422);
            }
            $nameFile = 'data-rekap-overtime-'.Str::slug($employee->name).'-'.date("Y-m-d", strtotime($period1)).'-'.date("Y-m-d", strtotime($period2)).'.xlsx';
            return Excel::download(new OvertimeWhereEmployeeExport, $nameFile);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function exportOvertimeUnit(Request $request)
    {
        try {
            $unitId = $request->input('unit');
            $period1 = $request->input('period_1');
            $period2 = $request->input('period_2');
            $unit = Unit::where('id', $unitId)->first(['id', 'name']);
            if (!$unit) {
                return $this->error('Unit Not Found', 422);
            }
            $nameFile = 'data-rekap-overtime-'.Str::slug($unit->name).'-'.date("Y-m-d", strtotime($period1)).'-'.date("Y-m-d", strtotime($period2)).'.xlsx';
            return Excel::download(new OvertimeWhereUnitExport, $nameFile);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function exportOvertimeDepartment(Request $request)
    {
        try {
            $departmentId = $request->input('department');
            $period1 = $request->input('period_1');
            $period2 = $request->input('period_2');
            $department = Department::where('id', $departmentId)->first(['id', 'name']);
            if (!$department) {
                return $this->error('Department Not Found', 422);
            }
            $nameFile = 'data-rekap-overtime-'.Str::slug($department->name).'-'.date("Y-m-d", strtotime($period1)).'-'.date("Y-m-d", strtotime($period2)).'.xlsx';
            return Excel::download(new OvertimeWhereDepartmentExport, $nameFile);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
