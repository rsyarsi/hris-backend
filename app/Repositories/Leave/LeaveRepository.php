<?php

namespace App\Repositories\Leave;

use Carbon\Carbon;
use App\Models\Shift;
use Illuminate\Support\Facades\DB;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\Firebase\FirebaseServiceInterface;
use App\Repositories\Leave\LeaveRepositoryInterface;
use App\Services\LeaveType\LeaveTypeServiceInterface;
use App\Services\CatatanCuti\CatatanCutiServiceInterface;
use App\Services\LeaveStatus\LeaveStatusServiceInterface;
use App\Services\LeaveHistory\LeaveHistoryServiceInterface;
use App\Services\GenerateAbsen\GenerateAbsenServiceInterface;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use App\Models\{CatatanCuti, Employee, GenerateAbsen, Leave, LeaveHistory, ShiftSchedule, User};

class LeaveRepository implements LeaveRepositoryInterface
{
    private $model;
    private $leaveHistory;
    private $leaveStatus;
    private $shiftScheduleService;
    private $firebaseService;
    private $employeeService;
    private $generateAbsenService;
    private $catatanCutiService;
    private $leaveTypeService;
    private $field = [
        'id',
        'employee_id',
        'leave_type_id',
        'from_date',
        'to_date',
        'duration',
        'note',
        'leave_status_id',
        'quantity_cuti_awal',
        'sisa_cuti',
        'file_url',
        'file_path',
        'file_disk',
        'shift_awal_id',
        'shift_schedule_id',
        'year',
        'created_at',
    ];

    public function __construct(
        Leave $model,
        LeaveHistoryServiceInterface $leaveHistory,
        LeaveStatusServiceInterface $leaveStatus,
        ShiftScheduleServiceInterface $shiftScheduleService,
        FirebaseServiceInterface $firebaseService,
        EmployeeServiceInterface $employeeService,
        GenerateAbsenServiceInterface $generateAbsenService,
        CatatanCutiServiceInterface $catatanCutiService,
        LeaveTypeServiceInterface $leaveTypeService
    ) {
        $this->model = $model;
        $this->leaveHistory = $leaveHistory;
        $this->leaveStatus = $leaveStatus;
        $this->shiftScheduleService = $shiftScheduleService;
        $this->firebaseService = $firebaseService;
        $this->employeeService = $employeeService;
        $this->generateAbsenService = $generateAbsenService;
        $this->catatanCutiService = $catatanCutiService;
        $this->leaveTypeService = $leaveTypeService;
    }

    public function index($perPage, $search = null, $period_1 = null, $period_2 = null, $unit = null)
    {
        $query = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'employment_number', 'unit_id');
                },
                'leaveType' => function ($query) {
                    $query->select('id', 'name', 'is_salary_deduction', 'active');
                },
                'leaveStatus' => function ($query) {
                    $query->select('id', 'name');
                },
                'leaveHistory' => function ($query) {
                    $query->select(
                        'id',
                        'leave_id',
                        'description',
                        'ip_address',
                        'user_id',
                        'user_agent',
                        'comment',
                    );
                },
                'shiftSchedule' => function ($query) {
                    $query->select(
                        'id',
                        'employee_id',
                        'shift_id',
                        'date',
                        'time_in',
                        'time_out',
                        'late_note',
                        'shift_exchange_id',
                        'user_exchange_id',
                        'user_exchange_at',
                        'created_user_id',
                        'updated_user_id',
                        'setup_user_id',
                        'setup_at',
                        'period',
                        'leave_note',
                        'holiday',
                        'night',
                        'national_holiday',
                        'leave_id'
                    );
                },
                'shift' => function ($query) {
                    $query->select(
                        'id',
                        'code',
                        'name',
                        'in_time',
                        'out_time',
                    );
                },
            ])
            ->select($this->field);
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                    ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                        $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                            ->orWhere('employment_number', 'like', '%' . $search . '%');
                    });
            });
        }
        if ($unit) {
            $query->whereHas('employee', function ($employeeQuery) use ($unit) {
                $employeeQuery->where('unit_id', $unit);
            });
        }
        if ($period_1) {
            $query->whereDate('from_date', '>=', $period_1);
        }
        if ($period_2) {
            $query->whereDate('to_date', '<=', $period_2);
        }
        return $query->orderBy('from_date', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $fromDate = Carbon::parse($data['from_date']);
            $toDate = Carbon::parse($data['to_date']);
            $employeeId = $data['employee_id'];
            // validate special case employee non shift
            $employee = DB::table('employees')->where('id', $employeeId)->first();
            $nonShiftGroupId = '01hfhe3aqcbw9r1fxvr2j2tb75';
            $shift = DB::table('shifts')->where('shift_group_id', $nonShiftGroupId)
                ->where('code', 'N')
                ->orWhere('name', 'NON SHIFT')
                ->first();
            $checkShiftSchedule = DB::table('shift_schedules')->where('employee_id', $employeeId)
                ->where('date', '>=', Carbon::parse($data['from_date'])->toDateString())
                ->where('date', '<=', Carbon::parse($data['to_date'])->toDateString())
                ->first();
            if ($employee->shift_group_id !== $nonShiftGroupId && !$checkShiftSchedule) {
                return [
                    'message' => 'Validation Error',
                    'error' => true,
                    'code' => 422,
                    'data' => ['from_date' => ['Data Shift Schedule belum ada, silahkan hubungi atasan!']]
                ];
            }
            $shiftAwalId = null;
            $shiftScheduleId = null;
            if ($checkShiftSchedule) {
                $shiftAwalId = $checkShiftSchedule->shift_id;
                $shiftScheduleId = $checkShiftSchedule->id;
            } else {
                if ($employee->shift_group_id == $nonShiftGroupId) {
                    // Loop through each date and create shift schedules
                    for ($date = $fromDate; $date->lte($toDate); $date->addDay()) {
                        // If shift schedule doesn't exist, create a new one
                        $newShiftSchedule =  ShiftSchedule::create([
                            'employee_id' => $employeeId,
                            'shift_id' => $shift->id,
                            'date' => $date->toDateString(),
                            'created_user_id' => auth()->id(),
                            'setup_user_id' => auth()->id(),
                            'setup_at' => now(),
                            'time_in' => $date->toDateString() . ' ' . $shift->in_time,
                            'time_out' => $date->toDateString() . ' ' . $shift->out_time,
                            'period' => now()->format('Y-m'),
                            'holiday' => 0,
                            'night' => 0,
                            'national_holiday' => 0,
                            'absen_type' => 'ABSEN',
                            'import' => 0,
                        ]);
                        $shiftAwalId = $newShiftSchedule->shift_id;
                        $shiftScheduleId = $newShiftSchedule->id;
                    }
                }
            }
            $data['shift_awal_id'] = $shiftAwalId;
            $data['shift_schedule_id'] = $shiftScheduleId;
            // create leave
            $leave = $this->model->create($data);
            // Update shift schedule if it exists
            if ($checkShiftSchedule) {
                $checkShiftScheduleModel = ShiftSchedule::find($checkShiftSchedule->id);
                // Make sure the shift schedule model is found
                if ($checkShiftScheduleModel) {
                    $checkShiftScheduleModel->update([
                        'leave_id' => $leave->id,
                        'leave_note' => $leave->note,
                    ]);
                }
            }
            $leaveType = $this->leaveTypeService->show($data['leave_type_id']);
            $leaveStatus = $this->leaveStatus->show($data['leave_status_id']);
            // create leave history
            $historyData = [
                'leave_id' => $leave->id,
                'user_id' => auth()->id(),
                'description' => 'LEAVE STATUS ' . $leaveStatus->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'comment' => $data['note'],
            ];
            $this->leaveHistory->store($historyData);
            // update shift schedule if exists in the table shift_schedules
            $fromDate = Carbon::parse($data['from_date']);
            $toDate = Carbon::parse($data['to_date']);
            $employeeId = $leave->employee_id;
            $this->shiftScheduleService->updateShiftSchedulesForLeave($employeeId, $fromDate, $toDate, $leave->id, $data['note']);
            // catatan cuti
            if ($data['leave_type_id'] == 1) {
                $catatanCutiLatest = $this->catatanCutiService->catatanCutiEmployeeLatest($leave->employee_id);
                if ($catatanCutiLatest === null) {
                    $quantityAkhirCatatan = 12;
                } else {
                    $quantityAkhirCatatan = $catatanCutiLatest->quantity_akhir;
                }
                $quantityOut = $fromDate->diffInDays($toDate);
                $quantityOut = $quantityOut == 0 ? 1 : ($quantityOut + 1);
                $quantityAkhir = (int)$quantityAkhirCatatan - (int)$quantityOut;
                $catatanCutiData = [
                    'adjustment_cuti_id' => null,
                    'leave_id' => $leave->id,
                    'employee_id' => $leave->employee_id,
                    'quantity_awal' => $quantityAkhirCatatan,
                    'quantity_akhir' => $quantityAkhir,
                    'quantity_in' => 0,
                    'quantity_out' => $quantityOut,
                    'type' => 'LEAVE',
                    'description' => $leaveType->name,
                    'batal' => 0,
                    'year' => $leave->year,
                ];
                $this->catatanCutiService->store($catatanCutiData);
                $updateLeave = [
                    'quantity_cuti_awal' => $quantityAkhirCatatan,
                    'sisa_cuti' => $quantityAkhir,
                ];
                $leave->update($updateLeave);
            }

            // firebase
            $employee = $this->employeeService->show($leave->employee_id);
            $registrationIds = [];
            if ($employee->supervisor != null) {
                if ($employee->supervisor->user != null) {
                    $registrationIds[] = $employee->supervisor->user->firebase_id;
                }
            }

            if ($employee->kabag_id != null) {
                if ($employee->kabag->user != null) {
                    $registrationIds[] = $employee->kabag->user->firebase_id;
                }
            }

            if ($employee->manager_id != null) {
                if ($employee->manager->user != null) {
                    $registrationIds[] = $employee->manager->user->firebase_id;
                }
            }
            $getEmployee = Employee::where('id', $data['employee_id'])->get()->first();
            // notif ke HRD
            $employeeHrd = User::where('hrd', '1')->where('username', '<>', $getEmployee->employment_number)->get();
            foreach ($employeeHrd as $key) {
                $firebaseIdx = $key;
            }
            $registrationIds[] = $firebaseIdx->firebase_id;
            if (!empty($registrationIds)) {
                $this->firebaseService->sendNotificationLeave($registrationIds, $employee->name);
            }
            DB::commit(); // Commit transaction if successful
            return [
                'message' => 'Leave created successfully',
                'error' => false,
                'code' => 200,
                'data' => [$leave]
            ];
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on errors
            return [
                'message' => 'Failed to create leave: ' . $e->getMessage(),
                'error' => true,
                'code' => 500,
                'data' => []
            ];
        }
    }

    public function leaveCreateMobile(array $data)
    {
        DB::beginTransaction();
        try {
            $fromDate = Carbon::parse($data['from_date']);
            $toDate = Carbon::parse($data['to_date']);
            $employeeId = $data['employee_id'];
            // validate special case employee non shift
            $employee = DB::table('employees')->where('id', $employeeId)->first();
            $nonShiftGroupId = '01hfhe3aqcbw9r1fxvr2j2tb75';
            $shift = DB::table('shifts')->where('shift_group_id', $nonShiftGroupId)
                ->where('code', 'N')
                ->orWhere('name', 'NON SHIFT')
                ->first();
            $checkShiftSchedule = DB::table('shift_schedules')->where('employee_id', $employeeId)
                ->where('date', '>=', Carbon::parse($data['from_date'])->toDateString())
                ->where('date', '<=', Carbon::parse($data['to_date'])->toDateString())
                ->first();
            if ($employee->shift_group_id !== $nonShiftGroupId && !$checkShiftSchedule) {
                return [
                    'message' => 'Data Shift Schedule belum ada, silahkan hubungi atasan!',
                    'success' => false,
                    'code' => 200,
                    'data' => ['from_date' => ['Data Shift Schedule belum ada, silahkan hubungi atasan!']]
                ];
            }
            $shiftAwalId = null;
            $shiftScheduleId = null;
            if ($checkShiftSchedule) {
                $shiftAwalId = $checkShiftSchedule->shift_id;
                $shiftScheduleId = $checkShiftSchedule->id;
            } else {
                if ($employee->shift_group_id == $nonShiftGroupId) {
                    // Loop through each date and create shift schedules
                    for ($date = $fromDate; $date->lte($toDate); $date->addDay()) {
                        // If shift schedule doesn't exist, create a new one
                        $newShiftSchedule =  ShiftSchedule::create([
                            'employee_id' => $employeeId,
                            'shift_id' => $shift->id,
                            'date' => $date->toDateString(),
                            'created_user_id' => auth()->id(),
                            'setup_user_id' => auth()->id(),
                            'setup_at' => now(),
                            'time_in' => $date->toDateString() . ' ' . $shift->in_time,
                            'time_out' => $date->toDateString() . ' ' . $shift->out_time,
                            'period' => now()->format('Y-m'),
                            'holiday' => 0,
                            'night' => 0,
                            'national_holiday' => 0,
                            'absen_type' => 'ABSEN',
                            'import' => 0,
                        ]);
                        $shiftAwalId = $newShiftSchedule->shift_id;
                        $shiftScheduleId = $newShiftSchedule->id;
                    }
                }
            }
            $data['shift_awal_id'] = $shiftAwalId;
            $data['shift_schedule_id'] = $shiftScheduleId;
            $leave = $this->model->create($data);
            $leaveType = $this->leaveTypeService->show($data['leave_type_id']);
            $leaveStatus = $this->leaveStatus->show($data['leave_status_id']);
            $historyData = [
                'leave_id' => $leave->id,
                'user_id' => auth()->id(),
                'description' => 'LEAVE STATUS ' . $leaveStatus->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'comment' => $data['note'],
            ];
            $this->leaveHistory->store($historyData);
            // update shift schedule if exists in the table shift_schedules
            $fromDate = Carbon::parse($data['from_date']);
            $toDate = Carbon::parse($data['to_date']);
            $employeeId = $leave->employee_id;
            $this->shiftScheduleService->updateShiftSchedulesForLeave($employeeId, $fromDate, $toDate, $leave->id, $data['note']);

            // catatan cuti
            if ($data['leave_type_id'] == 1) {
                $catatanCutiLatest = $this->catatanCutiService->catatanCutiEmployeeLatest($leave->employee_id);
                if ($catatanCutiLatest === null) {
                    $quantityAkhirCatatan = 12;
                } else {
                    $quantityAkhirCatatan = $catatanCutiLatest->quantity_akhir;
                }
                $quantityOut = $fromDate->diffInDays($toDate);
                $quantityOut = $quantityOut == 0 ? 1 : ($quantityOut + 1);
                $quantityAkhir = (int)$quantityAkhirCatatan - (int)$quantityOut;
                $catatanCutiData = [
                    'adjustment_cuti_id' => null,
                    'leave_id' => $leave->id,
                    'employee_id' => $leave->employee_id,
                    'quantity_awal' => $quantityAkhirCatatan,
                    'quantity_akhir' => $quantityAkhir,
                    'quantity_in' => 0,
                    'quantity_out' => $quantityOut,
                    'type' => 'LEAVE',
                    'description' => $leaveType->name,
                    'batal' => 0,
                    'year' => $leave->year,
                ];
                $this->catatanCutiService->store($catatanCutiData);
                $updateLeave = [
                    'quantity_cuti_awal' => $quantityAkhirCatatan,
                    'sisa_cuti' => $quantityAkhir,
                ];
                $leave->update($updateLeave);
            }

            // firebase
            $typeSend = 'Leaves';
            $employee = $this->employeeService->show($leave->employee_id);
            $registrationIds = [];
            if ($employee->supervisor != null) {
                if ($employee->supervisor->user != null) {
                    $registrationIds[] = $employee->supervisor->user->firebase_id;
                }
            }

            if ($employee->kabag_id != null) {
                if ($employee->kabag->user != null) {
                    $registrationIds[] = $employee->kabag->user->firebase_id;
                }
            }

            if ($employee->manager_id != null) {
                if ($employee->manager->user != null) {
                    $registrationIds[] = $employee->manager->user->firebase_id;
                }
            }
            $getEmployee = Employee::where('id', $data['employee_id'])->get()->first();
            // notif ke HRD
            $employeeHrd = User::where('hrd', '1')->where('username', '<>', $getEmployee->employment_number)->get();
            foreach ($employeeHrd as $key) {
                # code...
                $firebaseIdx = $key;
            }
            // dd($firebaseIdx->firebase_id);
            $registrationIds[] = $firebaseIdx->firebase_id;
            // Check if there are valid registration IDs before sending the notification
            if (!empty($registrationIds)) {
                $this->firebaseService->sendNotificationLeave($registrationIds, $employee->name);
            }
            DB::commit(); // Commit transaction if successful
            return [
                'message' => 'Leave created from mobile successfully',
                'success' => true,
                'code' => 200,
                'data' => [$leave]
            ];
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on errors

            return [
                'message' => 'Failed to create leave: ' . $e->getMessage(),
                'error' => true,
                'code' => 500,
                'data' => []
            ];
        }
    }

    public function show($id)
    {
        $leave = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'leaveType' => function ($query) {
                    $query->select('id', 'name', 'is_salary_deduction', 'active');
                },
                'leaveStatus' => function ($query) {
                    $query->select('id', 'name');
                },
                'leaveHistory' => function ($query) {
                    $query->select(
                        'id',
                        'leave_id',
                        'description',
                        'ip_address',
                        'user_id',
                        'user_agent',
                        'comment',
                    );
                },
                'shiftSchedule' => function ($query) {
                    $query->select(
                        'id',
                        'employee_id',
                        'shift_id',
                        'date',
                        'time_in',
                        'time_out',
                        'late_note',
                        'shift_exchange_id',
                        'user_exchange_id',
                        'user_exchange_at',
                        'created_user_id',
                        'updated_user_id',
                        'setup_user_id',
                        'setup_at',
                        'period',
                        'leave_note',
                        'holiday',
                        'night',
                        'national_holiday',
                        'leave_id'
                    );
                },
                'shift' => function ($query) {
                    $query->select(
                        'id',
                        'code',
                        'name',
                        'in_time',
                        'out_time',
                    );
                },
            ])
            ->where('id', $id)
            ->first($this->field);
        return $leave ? $leave : $leave = null;
    }

    public function update($id, $data)
    {
        // Start the database transaction
        DB::beginTransaction();
        try {
            $leave = $this->model->with('leaveStatus')->find($id);
            $historyData = [
                'leave_id' => $leave->id,
                'user_id' => auth()->id(),
                'description' => 'LEAVE STATUS ' . $leave->leaveStatus->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'comment' => $data['note'],
            ];
            $this->leaveHistory->store($historyData);
            // update shift schedule if exists in the table shift_schedules
            $fromDate = Carbon::parse($data['from_date']);
            $toDate = Carbon::parse($data['to_date']);
            $employeeId = $leave->employee_id;
            $this->shiftScheduleService->updateShiftSchedulesForLeave($employeeId, $fromDate, $toDate, $leave->id, $data['note']);

            // firebase
            $typeSend = 'Leaves';
            $employee = $this->employeeService->show($leave->employee_id);
            $registrationIds = [];
            if ($employee->supervisor != null) {
                if ($employee->supervisor->user != null) {
                    $registrationIds[] = $employee->supervisor->user->firebase_id;
                }
            }
            // if($employee->kabag_id != null ){
            //     if($employee->kabag->user != null){
            //         $registrationIds[] = $employee->kabag->user->firebase_id;
            //     }
            // }
            if ($employee->manager_id != null) {
                if ($employee->manager->user != null) {
                    $registrationIds[] = $employee->manager->user->firebase_id;
                }
            }
            // Check if there are valid registration IDs before sending the notification
            if (!empty($registrationIds)) {
                $this->firebaseService->sendNotification($registrationIds, $typeSend, $employee->name);
            }
            if ($leave) {
                $leave->update($data);
                // Commit the transaction
                DB::commit();
                return $leave;
            }
            return null;
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();

            // Handle the exception (log, throw, etc.)
            // For now, we'll just rethrow the exception
            throw $e;
        }
    }

    public function destroy($id)
    {
        $leave = $this->model->find($id);
        if ($leave) {
            $this->leaveHistory->deleteByLeaveId($id);
            $this->shiftScheduleService->deleteByLeaveId($leave->employee_id, $id);
            $leave->delete();
            return $leave;
        }
        return null;
    }

    public function leaveEmployee($perPage, $leaveStatus = null, $startDate = null, $endDate = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }
        $query = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'leaveType' => function ($query) {
                    $query->select('id', 'name', 'is_salary_deduction', 'active');
                },
                'leaveStatus' => function ($query) {
                    $query->select('id', 'name');
                },
                'leaveHistory' => function ($query) {
                    $query->select(
                        'id',
                        'leave_id',
                        'description',
                        'ip_address',
                        'user_id',
                        'user_agent',
                        'comment',
                    );
                }
            ])
            ->select($this->field);
        $query->where('employee_id', $user->employee->id);
        if ($leaveStatus) {
            $query->where('leave_status_id', $leaveStatus);
        }
        if ($startDate) {
            $query->whereDate('from_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('from_date', '<=', $endDate);
        }
        return $query->paginate($perPage);
    }

    public function leaveEmployeeMobile($employeeId)
    {
        $employee = Employee::where('employment_number', $employeeId)->first();
        if (!$employee) {
            return [];
        }

        $leave = DB::table('leaves')
            ->select([
                DB::raw("COALESCE(leaves.id, '') as id"),
                DB::raw("COALESCE(leaves.employee_id, '') as employee_id"),
                DB::raw("COALESCE(TO_CHAR(leaves.from_date, 'YYYY-MM-DD HH24:MI:SS'), '') as from_date"),
                DB::raw("COALESCE(TO_CHAR(leaves.to_date, 'YYYY-MM-DD HH24:MI:SS'), '') as to_date"),
                DB::raw("COALESCE(TO_CHAR(leaves.from_date, 'HH24:MI:SS'), '') as from_time"),
                DB::raw("COALESCE(TO_CHAR(leaves.to_date, 'HH24:MI:SS'), '') as to_time"),
                DB::raw("COALESCE(leaves.duration::text, '') as duration"),
                DB::raw("COALESCE(leaves.note, '') as note"),
                DB::raw("COALESCE(leaves.file_url, '') as file_url"),
                DB::raw("COALESCE(leaves.file_path, '') as file_path"),
                DB::raw("COALESCE(leaves.file_disk, '') as file_disk"),
                DB::raw("COALESCE(employees.name, '') as employee_name"),
                DB::raw("COALESCE(leave_types.id::text, '') as leave_type_id"),
                DB::raw("COALESCE(leave_types.name, '') as leave_type_name"),
                DB::raw("COALESCE(leave_types.is_salary_deduction::text, '') as is_salary_deduction"),
                DB::raw("COALESCE(leaves.leave_status_id::text, '') as leave_status_id"),
                DB::raw("COALESCE(leave_statuses.name, '') as overtime_status_name"),
                // DB::raw("COALESCE(leave_histories.description, '') as history_description"),
            ])
            ->leftJoin('employees', 'leaves.employee_id', '=', 'employees.id')
            ->leftJoin('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
            ->leftJoin('leave_statuses', 'leaves.leave_status_id', '=', 'leave_statuses.id')
            // ->leftJoin('leave_histories', 'leaves.id', '=', 'leave_histories.leave_id')
            ->where('leaves.employee_id', $employee->id)
            ->orderBy('leaves.from_date', 'DESC')
            // ->groupBy('leaves.id', 'leaves.employee_id', 'leaves.from_date', 'leaves.to_date', 'leaves.duration', 'leaves.note', 'employees.name', 'leave_types.id', 'leave_types.name', 'leave_types.is_salary_deduction', 'leave_statuses.name', 'leave_histories.description')
            ->get();
        return $leave ? $leave : $leave = null;
    }

    public function leaveHrdMobile()
    {
        return DB::table('leaves')
            ->leftJoin('employees', 'leaves.employee_id', '=', 'employees.id')
            ->leftJoin('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
            ->leftJoin('leave_statuses', 'leaves.leave_status_id', '=', 'leave_statuses.id')
            ->select([
                'leaves.id',
                'leaves.employee_id',
                'leaves.leave_type_id',
                'leaves.from_date',
                'leaves.to_date',
                'leaves.duration',
                'leaves.note',
                'leaves.leave_status_id',
                'leaves.quantity_cuti_awal',
                'leaves.sisa_cuti',
                'leaves.file_url',
                'leaves.file_path',
                'leaves.file_disk',
                'employees.name as employee_name',
                'leave_types.name as leave_type_name',
                'leave_statuses.name as leave_status_name',
            ])
            ->whereIn('leave_status_id', [1, 2, 3, 4])
            ->orderBy('from_date', 'DESC')
            ->get();
    }

    public function leaveSupervisorOrManager($perPage, $search, $leaveStatus = null, $startDate = null, $endDate = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }

        // Get employees supervised or managed by the logged-in user
        $subordinateIds = Employee::where('supervisor_id', $user->employee->id)
            ->orWhere('manager_id', $user->employee->id)
            ->orWhere('kabag_id', $user->employee->id)
            ->pluck('id');

        $query = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'leaveType' => function ($query) {
                    $query->select('id', 'name', 'is_salary_deduction', 'active');
                },
                'leaveStatus' => function ($query) {
                    $query->select('id', 'name');
                },
                'leaveHistory' => function ($query) {
                    $query->select(
                        'id',
                        'leave_id',
                        'description',
                        'ip_address',
                        'user_id',
                        'user_agent',
                        'comment'
                    );
                }
            ])
            ->select($this->field);

        // Filter leave data for supervised or managed employees
        $query->whereIn('employee_id', $subordinateIds);
        if ($leaveStatus) {
            $query->whereIn('leave_status_id', $leaveStatus);
        }
        if ($startDate) {
            $query->whereDate('from_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('from_date', '<=', $endDate);
        }
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                    ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                        $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                            ->orWhere('employment_number', 'like', '%' . $search . '%');
                    });
            });
        }
        return $query->orderBy('from_date', 'DESC')->paginate($perPage);
    }

    public function leaveSupervisorOrManagerMobile($employeeId)
    {
        // Get employees supervised or managed by the logged-in user
        $subordinateIds = Employee::where('supervisor_id', $employeeId)
            ->orWhere('manager_id', $employeeId)
            ->orWhere('kabag_id', $employeeId)
            ->pluck('id');
        return DB::table('leaves')
            ->leftJoin('employees', 'leaves.employee_id', '=', 'employees.id')
            ->leftJoin('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
            ->leftJoin('leave_statuses', 'leaves.leave_status_id', '=', 'leave_statuses.id')
            // ->leftJoin('leave_histories', 'leaves.id', '=', 'leave_histories.leave_id')
            ->select([
                'leaves.id',
                'leaves.employee_id',
                'leaves.leave_type_id',
                'leaves.from_date',
                'leaves.to_date',
                'leaves.duration',
                'leaves.note',
                'leaves.leave_status_id',
                'leaves.quantity_cuti_awal',
                'leaves.sisa_cuti',
                'leaves.file_url',
                'leaves.file_path',
                'leaves.file_disk',
                'employees.name as employee_name',
                'leave_types.name as leave_type_name',
                'leave_statuses.name as leave_status_name',
                // 'leave_histories.id as leave_history_id',
                // 'leave_histories.description as leave_histories_description',
                // 'leave_histories.ip_address as leave_histories_ip_address',
                // 'leave_histories.user_id as leave_histories_user_id',
                // 'leave_histories.user_agent as leave_histories_user_agent',
                // 'leave_histories.comment as leave_histories_comment',
            ])
            ->whereIn('employee_id', $subordinateIds)
            ->whereNotIn('leaves.leave_status_id', [6, 7, 8, 9, 10])
            ->orderBy('leaves.from_date', 'DESC')
            ->get();
    }

    public function leaveStatus($perPage, $search = null, $period_1 = null, $period_2 = null, $leaveStatus = null, $unit)
    {
        $query = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'employment_number', 'unit_id');
                },
                'leaveType' => function ($query) {
                    $query->select('id', 'name', 'is_salary_deduction', 'active');
                },
                'leaveStatus' => function ($query) {
                    $query->select('id', 'name');
                },
                'leaveHistory' => function ($query) {
                    $query->select(
                        'id',
                        'leave_id',
                        'description',
                        'ip_address',
                        'user_id',
                        'user_agent',
                        'comment',
                    );
                }
            ])
            ->select($this->field);
        if ($leaveStatus) {
            $query->whereIn('leave_status_id', $leaveStatus);
        }
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                    ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                        $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                            ->orWhere('employment_number', 'like', '%' . $search . '%');
                    });
            });
        }
        if ($unit) {
            $query->whereHas('employee', function ($employeeQuery) use ($unit) {
                $employeeQuery->where('unit_id', $unit);
            });
        }
        if ($period_1) {
            $query->whereDate('from_date', '>=', $period_1);
        }
        if ($period_2) {
            $query->whereDate('to_date', '<=', $period_2);
        }
        return $query->orderBy('created_at', 'DESC')->paginate($perPage);
    }

    public function updateStatus($id, $data)
    {
        // Start the database transaction
        DB::beginTransaction();
        try {
            $leave = $this->model->find($id);
            $status = $data['leave_status_id'];
            $leaveStatus = $this->leaveStatus->show($data['leave_status_id']);
            // $date = Carbon::parse($leave->from_date);
            if ($status == 5) { // if approval HRD
                $startDate = Carbon::parse($leave->from_date);
                $endDate = Carbon::parse($leave->to_date);
                $employee = Employee::where('id', $leave->employee_id)->first();
                while ($startDate->lte($endDate)) {
                    // search shift schedule employee_id and date shift
                    $shiftScheduleDate = $this->shiftScheduleService->shiftScheduleEmployeeDate($leave->employee_id, $startDate->toDateString());
                    // shift schedule non shift
                    $nonShiftGroupId = '01hfhe3aqcbw9r1fxvr2j2tb75';
                    $shift = Shift::where('shift_group_id', $nonShiftGroupId)
                        ->where('code', 'N')
                        ->orWhere('name', 'NON SHIFT')
                        ->first();
                    $timeInAt = null;
                    $timeOutAt = null;
                    $shiftId = null;
                    $scheduleTimeInAt = null;
                    $scheduleTimeOutAt = null;
                    if ($leave->leave_type_id == 1) { // CUTI TAHUNAN
                        $timeInAt = '00:00:00';
                        $timeOutAt = '00:00:00';
                        $shiftId = $shiftScheduleDate->shift_id;
                        $scheduleTimeInAt = '00:00:00';
                        $scheduleTimeOutAt = '00:00:00';
                    } else if ($leave->leave_type_id !== 1 && $employee->shift_group_id == $nonShiftGroupId) { // NON SHIFT
                        $timeInAt = $shift->in_time;
                        $timeOutAt = $shift->out_time;
                        $shiftId = $shift->id;
                        $scheduleTimeInAt = $shift->in_time;
                        $scheduleTimeOutAt = $shift->out_time;
                    } else if ($leave->leave_type_id !== 1 && $employee->shift_group_id !== $nonShiftGroupId) { // SHIFT
                        $timeInAt = $shiftScheduleDate->shift->in_time;
                        $timeOutAt = $shiftScheduleDate->shift->out_time;
                        $shiftId = $shiftScheduleDate->shift_id;
                        $scheduleTimeInAt = $shiftScheduleDate->shift->in_time;
                        $scheduleTimeOutAt = $shiftScheduleDate->shift->out_time;
                    }
                    $absen = $this->generateAbsenService->findDate($leave->employee_id, $startDate->toDateString());
                    $dataAbsen = [
                        'period' => $startDate->format('Y-m'),
                        'employee_id' => $leave->employee_id,
                        'date' => $startDate->toDateString(),
                        'day' => $startDate->format('l'),
                        'leave_id' => $leave->id,
                        'leave_type_id' => $leave->leave_type_id,
                        'leave_time_at' => $leave->from_date,
                        'leave_out_at' => $leave->to_date,
                        'schedule_leave_time_at' => $leave->from_date,
                        'schedule_leave_out_at' => $leave->to_date,
                        'shift_schedule_id' => $leave->shift_schedule_id,
                        'date_in_at' => $startDate->toDateString(),
                        'time_in_at' => $timeInAt,
                        'date_out_at' => $startDate->toDateString(),
                        'time_out_at' => $timeOutAt,
                        'employment_id' => $employee->employment_number,
                        'shift_id' => $shiftId,
                        'schedule_date_in_at' => $startDate->toDateString(),
                        'schedule_time_in_at' => $scheduleTimeInAt,
                        'schedule_date_out_at' => $startDate->toDateString(),
                        'schedule_time_out_at' => $scheduleTimeOutAt,
                    ];
                    if (!$absen) { // if in the table generate_absen not exists -> create the data.
                        $this->generateAbsenService->store($dataAbsen);
                    } else {
                        $this->generateAbsenService->update($absen->id, $dataAbsen);
                    }
                    $startDate->addDay(); // Move to the next day
                }
            }
            // if ($status == 10) { // if batal HRD
            //     $shiftScheduleUpdate = ShiftSchedule::where('leave_id', $id)->first();
            //     $shiftScheduleUpdate->update([
            //         'leave_id' => null,
            //         'leave_note' => null,
            //     ]);
            // }
            if ($leave) {
                $leave->update(['leave_status_id' => $status]);
                $historyData = [
                    'leave_id' => $leave->id,
                    'user_id' => auth()->id(),
                    'description' => 'LEAVE STATUS ' . $leaveStatus->name,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'comment' => $leave->note,
                ];
                $this->leaveHistory->store($historyData);
                if (in_array($status, [6, 7, 8, 9, 10])) {
                    ShiftSchedule::where('leave_id', $leave->id)
                        ->update([
                            'leave_id' => null,
                            'leave_note' => null
                        ]);
                }
                // update data batal catatan cuti
                if ($leave->leave_type_id == 1 && in_array($status, [6, 7, 8, 9, 10])) {
                    $catatanCuti = CatatanCuti::where('leave_id', $leave->id)
                        ->latest()
                        ->first();
                    // update catatan cuti
                    // $catatanCuti->update([
                    //     'quantity_akhir' => $catatanCuti->quantity_awal,
                    //     'quantity_out' => 0,
                    //     'batal' => 1
                    // ]);
                    $newCatatanCuti = CatatanCuti::create([
                        'adjustment_cuti_id' => null,
                        'leave_id' => $catatanCuti->leave_id,
                        'employee_id' => $catatanCuti->employee_id,
                        'quantity_awal' => $catatanCuti->quantity_akhir,
                        'quantity_akhir' => (int)$catatanCuti->quantity_akhir + (int)$catatanCuti->quantity_out,
                        'quantity_in' => $catatanCuti->quantity_out,
                        'quantity_out' => 0,
                        'type' => 'LEAVE',
                        'description' => $leaveStatus->name,
                        'batal' => 1,
                        'year' => $catatanCuti->year,
                    ]);

                    // $employee = $this->employeeService->show($leave->employee_id);
                    // update Shift Schedule if shift, delete if non shift
                    // if ($employee->shift_group_id == '01hfhe3aqcbw9r1fxvr2j2tb75') {
                    //     ShiftSchedule::where('leave_id', $leave->id)->delete();
                    //     LeaveHistory::where('leave_id', $leave->id)->delete();
                    //     $leave->delete();
                    // } else {
                    $leave->update([
                        'quantity_cuti_awal' => $newCatatanCuti->quantity_akhir,
                        'sisa_cuti' => $newCatatanCuti->quantity_akhir
                    ]);
                    // }
                }

                // firebase
                $typeSend = 'Cuti/Izin';
                $employee = $this->employeeService->show($leave->employee_id);
                $registrationIds = [];
                if ($employee->user != null) {
                    $registrationIds[] = $employee->user->firebase_id;
                }
                // notif ke HRDs
                $employeeHrd = User::where('hrd', '1')->get();
                foreach ($employeeHrd as $key) {
                    # code...
                    $firebaseIdx = $key;
                }
                $registrationIds[] = $firebaseIdx->firebase_id;

                // Check if there are valid registration IDs before sending the notification
                if (!empty($registrationIds)) {
                    $this->firebaseService->sendNotification($registrationIds, $typeSend, $employee->name);
                }
                // Commit the transaction
                DB::commit();
                return $leave;
            }
            return null;
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();

            // Handle the exception (log, throw, etc.)
            // For now, we'll just rethrow the exception
            throw $e;
        }
    }

    public function updateStatusMobile($leaveId, $leaveStatusId)
    {
        // Start the database transaction
        DB::beginTransaction();
        try {
            $leave = $this->model->find($leaveId);
            $leaveStatus = $this->leaveStatus->show($leaveStatusId);
            if ($leaveStatusId == 5) { // if approval HRD
                $startDate = Carbon::parse($leave->from_date);
                $endDate = Carbon::parse($leave->to_date);
                $employee = Employee::where('id', $leave->employee_id)->first();
                while ($startDate->lte($endDate)) {
                    // search shift schedule employee_id and date shift
                    $shiftScheduleDate = $this->shiftScheduleService->shiftScheduleEmployeeDate($leave->employee_id, $startDate->toDateString());
                    // shift schedule non shift
                    $nonShiftGroupId = '01hfhe3aqcbw9r1fxvr2j2tb75';
                    $shift = Shift::where('shift_group_id', $nonShiftGroupId)
                        ->where('code', 'N')
                        ->orWhere('name', 'NON SHIFT')
                        ->first();
                    $timeInAt = null;
                    $timeOutAt = null;
                    $shiftId = null;
                    $scheduleTimeInAt = null;
                    $scheduleTimeOutAt = null;
                    if ($leave->leave_type_id == 1) { // CUTI TAHUNAN
                        $timeInAt = '00:00:00';
                        $timeOutAt = '00:00:00';
                        $shiftId = $shiftScheduleDate->shift_id;
                        $scheduleTimeInAt = '00:00:00';
                        $scheduleTimeOutAt = '00:00:00';
                    } else if ($leave->leave_type_id !== 1 && $employee->shift_group_id == $nonShiftGroupId) { // NON SHIFT
                        $timeInAt = $shift->in_time;
                        $timeOutAt = $shift->out_time;
                        $shiftId = $shift->id;
                        $scheduleTimeInAt = $shift->in_time;
                        $scheduleTimeOutAt = $shift->out_time;
                    } else if ($leave->leave_type_id !== 1 && $employee->shift_group_id !== $nonShiftGroupId) { // SHIFT
                        $timeInAt = $shiftScheduleDate->shift->in_time;
                        $timeOutAt = $shiftScheduleDate->shift->out_time;
                        $shiftId = $shiftScheduleDate->shift_id;
                        $scheduleTimeInAt = $shiftScheduleDate->shift->in_time;
                        $scheduleTimeOutAt = $shiftScheduleDate->shift->out_time;
                    }
                    $absen = $this->generateAbsenService->findDate($leave->employee_id, $startDate->toDateString());
                    $dataAbsen = [
                        'period' => $startDate->format('Y-m'),
                        'employee_id' => $leave->employee_id,
                        'date' => $startDate->toDateString(),
                        'day' => $startDate->format('l'),
                        'leave_id' => $leave->id,
                        'leave_type_id' => $leave->leave_type_id,
                        'leave_time_at' => $leave->from_date,
                        'leave_out_at' => $leave->to_date,
                        'schedule_leave_time_at' => $leave->from_date,
                        'schedule_leave_out_at' => $leave->to_date,
                        'shift_schedule_id' => $leave->shift_schedule_id,
                        'date_in_at' => $startDate->toDateString(),
                        'time_in_at' => $timeInAt,
                        'date_out_at' => $startDate->toDateString(),
                        'time_out_at' => $timeOutAt,
                        'employment_id' => $employee->employment_number,
                        'shift_id' => $shiftId,
                        'schedule_date_in_at' => $startDate->toDateString(),
                        'schedule_time_in_at' => $scheduleTimeInAt,
                        'schedule_date_out_at' => $startDate->toDateString(),
                        'schedule_time_out_at' => $scheduleTimeOutAt,
                    ];
                    if (!$absen) { // if in the table generate_absen not exists -> create the data.
                        $this->generateAbsenService->store($dataAbsen);
                    } else {
                        $this->generateAbsenService->update($absen->id, $dataAbsen);
                    }
                    $startDate->addDay(); // Move to the next day
                }
            }
            if ($leave) {
                // update leave status in the table leaves
                $leave->update(['leave_status_id' => $leaveStatusId]);
                // create leave history in the table leaves history
                $historyData = [
                    'leave_id' => $leave->id,
                    'user_id' => auth()->id(),
                    'description' => 'LEAVE STATUS ' . $leaveStatus->name,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'comment' => $leave->note,
                ];
                $this->leaveHistory->store($historyData);
                if (in_array($leaveStatusId, [6, 7, 8, 9, 10])) {
                    ShiftSchedule::where('leave_id', $leave->id)
                        ->update([
                            'leave_id' => null,
                            'leave_note' => null
                        ]);
                }
                // update data batal catatan cuti
                if ($leave->leave_type_id == 1 && in_array($leaveStatusId, [6, 7, 8, 9, 10])) {
                    $catatanCuti = CatatanCuti::where('leave_id', $leave->id)
                        ->latest()
                        ->first();
                    // update catatan cuti
                    // $catatanCuti->update([
                    //     'quantity_akhir' => $catatanCuti->quantity_awal,
                    //     'quantity_out' => 0,
                    //     'batal' => 1
                    // ]);
                    $newCatatanCuti = CatatanCuti::create([
                        'adjustment_cuti_id' => null,
                        'leave_id' => $catatanCuti->leave_id,
                        'employee_id' => $catatanCuti->employee_id,
                        'quantity_awal' => $catatanCuti->quantity_akhir,
                        'quantity_akhir' => (int)$catatanCuti->quantity_akhir + (int)$catatanCuti->quantity_out,
                        'quantity_in' => $catatanCuti->quantity_out,
                        'quantity_out' => 0,
                        'type' => 'LEAVE',
                        'description' => $leaveStatus->name,
                        'batal' => 1,
                        'year' => $catatanCuti->year,
                    ]);

                    // $employee = $this->employeeService->show($leave->employee_id);
                    // update Shift Schedule if shift, delete if non shift
                    // if ($employee->shift_group_id == '01hfhe3aqcbw9r1fxvr2j2tb75') {
                    //     ShiftSchedule::where('leave_id', $leave->id)->delete();
                    //     LeaveHistory::where('leave_id', $leave->id)->delete();
                    //     $leave->delete();
                    // } else {
                    $leave->update([
                        'quantity_cuti_awal' => $newCatatanCuti->quantity_akhir,
                        'sisa_cuti' => $newCatatanCuti->quantity_akhir
                    ]);
                    // }
                }

                // firebase
                $typeSend = 'Cuti/Izin';
                $employee = $this->employeeService->show($leave->employee_id);
                $registrationIds = [];
                if ($employee->user != null) {
                    $registrationIds[] = $employee->user->firebase_id;
                }

                // notif ke HRDs
                $employeeHrd = User::where('hrd', '1')->get();
                foreach ($employeeHrd as $key) {
                    # code...
                    $firebaseIdx = $key;
                }
                $registrationIds[] = $firebaseIdx->firebase_id;
                // Check if there are valid registration IDs before sending the notification
                if (!empty($registrationIds)) {
                    $this->firebaseService->sendNotification($registrationIds, $typeSend, $employee->name);
                }
                // Commit the transaction
                DB::commit();
                return $leave;
            }
            return null;
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();

            // Handle the exception (log, throw, etc.)
            // For now, we'll just rethrow the exception
            throw $e;
        }
    }

    function leaveSisa($employeeId)
    {
        $sisaCutiLatest = $this->catatanCutiService->catatanCutiEmployeeLatest($employeeId);
        $employee = $this->employeeService->show($employeeId);
        return [
            'leaves_qty_akhir' => $sisaCutiLatest->quantity_akhir,
            'employee_id' => $employeeId,
            'name' => $employee->name,
            'employment_number' => $employee->employment_number,
            'unit_id' => $employee->unit_id,
        ];
    }
}
