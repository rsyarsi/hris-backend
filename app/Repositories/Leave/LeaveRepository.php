<?php

namespace App\Repositories\Leave;

use Carbon\Carbon;
use App\Models\{Employee, Leave};
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
                        }
                    ])
                    ->where('employee_id', $employee->id)
                    ->orderBy('from_date', 'ASC')
                    ->get($this->field);
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
