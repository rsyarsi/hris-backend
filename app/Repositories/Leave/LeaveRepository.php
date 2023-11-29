<?php

namespace App\Repositories\Leave;

use Carbon\Carbon;
use App\Models\{Employee, Leave};
use Illuminate\Support\Facades\DB;
use App\Repositories\Leave\LeaveRepositoryInterface;
use App\Services\LeaveStatus\LeaveStatusServiceInterface;
use App\Services\LeaveHistory\LeaveHistoryServiceInterface;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;

class LeaveRepository implements LeaveRepositoryInterface
{
    private $model;
    private $leaveHistory;
    private $leaveStatus;
    private $shiftSchedule;

    private $field = [
        'id',
        'employee_id',
        'leave_type_id',
        'from_date',
        'to_date',
        'duration',
        'note',
        'leave_status_id',
    ];

    public function __construct(Leave $model, LeaveHistoryServiceInterface $leaveHistory, LeaveStatusServiceInterface $leaveStatus, ShiftScheduleServiceInterface $shiftSchedule)
    {
        $this->model = $model;
        $this->leaveHistory = $leaveHistory;
        $this->leaveStatus = $leaveStatus;
        $this->shiftSchedule = $shiftSchedule;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name');
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
                    ])
                    ->select($this->field);
        return $query->orderBy('from_date', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        $leave = $this->model->create($data);
        $leaveStatus = $this->leaveStatus->show($data['leave_status_id']);
        $historyData = [
            'leave_id' => $leave->id,
            'user_id' => auth()->id(),
            'description' => 'LEAVE STATUS '. $leaveStatus->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'comment' => $data['note'],
        ];
        $this->leaveHistory->store($historyData);
        // update shift schedule if exists in the table shift_schedules
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $employeeId = $leave->employee_id;
        $this->shiftSchedule->updateShiftSchedulesForLeave($employeeId, $fromDate, $toDate, $leave->id, $data['note']);
        return $leave;
    }

    public function show($id)
    {
        $leave = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name');
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
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $leave ? $leave : $leave = null;
    }

    public function update($id, $data)
    {
        $leave = $this->model->with('leaveStatus')->find($id);
        $historyData = [
            'leave_id' => $leave->id,
            'user_id' => auth()->id(),
            'description' => $leave->leaveStatus->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'comment' => $data['note'],
        ];
        $this->leaveHistory->store($historyData);
        if ($leave) {
            $leave->update($data);
            return $leave;
        }
        return null;
    }

    public function destroy($id)
    {
        $leave = $this->model->find($id);
        if ($leave) {
            $this->leaveHistory->deleteByLeaveId($id);
            $this->shiftSchedule->deleteByLeaveId($leave->employee_id, $id);
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
                            $query->select('id', 'name');
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
        // $leave = DB::table('leaves')
        //             ->select([
        //                 'leaves.id',
        //                 'leaves.employee_id',
        //                 DB::raw("COALESCE(TO_CHAR(leaves.from_date, 'YYYY-MM-DD HH24:MI:SS'), '') as from_date"),
        //                 DB::raw("COALESCE(TO_CHAR(leaves.to_date, 'YYYY-MM-DD HH24:MI:SS'), '') as to_date"),
        //                 DB::raw("COALESCE(leaves.duration::text, '') as duration"),
        //                 DB::raw("COALESCE(leaves.note, '') as note"),
        //                 'employees.name as employee_name',
        //                 'leave_types.id as leave_type_id',
        //                 'leave_types.name as leave_type_name',
        //                 DB::raw("COALESCE(leave_types.is_salary_deduction::text, '') as is_salary_deduction"),
        //                 'leave_statuses.name as overtime_status_name',
        //                 DB::raw("JSON_AGG(
        //                     JSON_BUILD_OBJECT(
        //                         'id', leave_histories.id,
        //                         'leave_id', leave_histories.leave_id,
        //                         'description', leave_histories.description,
        //                         'ip_address', leave_histories.ip_address,
        //                         'user_id', leave_histories.user_id,
        //                         'user_agent', leave_histories.user_agent,
        //                         'comment', leave_histories.comment
        //                     )
        //                 ) as leave_history"),
        //             ])
        //             ->leftJoin('employees', 'leaves.employee_id', '=', 'employees.id')
        //             ->leftJoin('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
        //             ->leftJoin('leave_statuses', 'leaves.leave_status_id', '=', 'leave_statuses.id')
        //             ->leftJoin('leave_histories', 'leaves.id', '=', 'leave_histories.leave_id')
        //             ->where('leaves.employee_id', $employee->id)
        //             ->orderBy('leaves.from_date', 'ASC')
        //             ->groupBy('leaves.id', 'leaves.employee_id', 'leaves.from_date', 'leaves.to_date', 'leaves.duration', 'leaves.note', 'employees.name', 'leave_types.id', 'leave_types.name', 'leave_types.is_salary_deduction', 'leave_statuses.name')
        //             ->get();

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
                        DB::raw("COALESCE(employees.name, '') as employee_name"),
                        DB::raw("COALESCE(leave_types.id::text, '') as leave_type_id"),
                        DB::raw("COALESCE(leave_types.name, '') as leave_type_name"),
                        DB::raw("COALESCE(leave_types.is_salary_deduction::text, '') as is_salary_deduction"),
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

    public function leaveSupervisorOrManager($perPage, $leaveStatus = null, $startDate = null, $endDate = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }

        // Get employees supervised or managed by the logged-in user
        $subordinateIds = Employee::where('supervisor_id', $user->employee->id)
                                    ->orWhere('manager_id', $user->employee->id)
                                    ->pluck('id');

        $query = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name');
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

    public function leaveStatus($perPage, $search = null, $leaveStatus = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name');
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
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->where('name', 'like', '%' . $search . '%');
                            });
            });
        }

        if ($leaveStatus) {
            $query->where('leave_status_id', $leaveStatus);
        }
        return $query->paginate($perPage);
    }

    public function updateStatus($id, $data)
    {
        $leave = $this->model->find($id);
        if ($leave) {
            $leave->update(['leave_status_id' => $data['leave_status_id']]);
            $leaveStatus = $this->leaveStatus->show($data['leave_status_id']);
            $historyData = [
                'leave_id' => $leave->id,
                'user_id' => auth()->id(),
                'description' => 'LEAVE STATUS '. $leaveStatus->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'comment' => $leave->note,
            ];
            $this->leaveHistory->store($historyData);
            return $leave;
        }
        return null;
    }
}
