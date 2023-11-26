<?php

namespace App\Repositories\ShiftSchedule;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\ShiftSchedule;
use Illuminate\Support\Facades\DB;
use App\Repositories\ShiftSchedule\ShiftScheduleRepositoryInterface;

class ShiftScheduleRepository implements ShiftScheduleRepositoryInterface
{
    private $model;
    private $field = [
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
    ];

    public function __construct(ShiftSchedule $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null, $startDate = null, $endDate = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'shift' => function ($query) {
                            $query->select(
                                'id',
                                'shift_group_id',
                                'code',
                                'name',
                                'in_time',
                                'out_time',
                                'finger_in_less',
                                'finger_in_more',
                                'finger_out_less',
                                'finger_out_more',
                                'night_shift',
                                'active',
                                'user_created_id',
                                'user_updated_id',
                            )->with('shiftGroup:id,name');
                        },
                        // 'shiftExcange' => function ($query) {
                        //     $query->select(
                        //         'id',
                        //         'employee_id',
                        //         'leave_type_id',
                        //         'from_date',
                        //         'to_date',
                        //         'duration',
                        //         'note',
                        //         'leave_status_id',
                        //     );
                        // },
                        'userExchange' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'userCreate' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'userUpdate' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'userSetup' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'leave' => function ($query) {
                            $query->select(
                                'employee_id',
                                'leave_type_id',
                                'from_date',
                                'to_date',
                                'duration',
                                'note',
                                'leave_status_id',
                            );
                        },
                    ])
                    ->select($this->field);
                    if ($search) {
                        $query->where(function ($subquery) use ($search) {
                            $subquery->orWhere('employee_id', $search)
                                        ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                            $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
                                        });
                        });
                    }
                    if ($startDate) {
                        $query->whereDate('date', '>=', $startDate);
                    }
                    if ($endDate) {
                        $query->whereDate('date', '<=', $endDate);
                    }
        return $query->orderBy('date', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $shiftschedule = $this->model
                            ->with([
                                'employee' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'shift' => function ($query) {
                                    $query->select(
                                        'id',
                                        'shift_group_id',
                                        'code',
                                        'name',
                                        'in_time',
                                        'out_time',
                                        'finger_in_less',
                                        'finger_in_more',
                                        'finger_out_less',
                                        'finger_out_more',
                                        'night_shift',
                                        'active',
                                        'user_created_id',
                                        'user_updated_id',
                                    );
                                },
                                // 'shiftExcange' => function ($query) {
                                //     $query->select(
                                //         'id',
                                //         'employee_id',
                                //         'leave_type_id',
                                //         'from_date',
                                //         'to_date',
                                //         'duration',
                                //         'note',
                                //         'leave_status_id',
                                //     );
                                // },
                                'userExchange' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'userCreate' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'userUpdate' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'userSetup' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'leave' => function ($query) {
                                    $query->select(
                                        'employee_id',
                                        'leave_type_id',
                                        'from_date',
                                        'to_date',
                                        'duration',
                                        'note',
                                        'leave_status_id',
                                    );
                                },
                            ])
                        ->where('id', $id)
                        ->first($this->field);
        return $shiftschedule ? $shiftschedule : $shiftschedule = null;
    }

    public function update($id, $data)
    {
        $shiftschedule = $this->model->find($id);
        if ($shiftschedule) {
            $shiftschedule->update($data);
            return $shiftschedule;
        }
        return null;
    }

    public function destroy($id)
    {
        $shiftschedule = $this->model->find($id);
        if ($shiftschedule) {
            $shiftschedule->delete();
            return $shiftschedule;
        }
        return null;
    }

    public function shiftScheduleEmployee($perPage, $startDate = null, $endDate = null)
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
                        'shift' => function ($query) {
                            $query->select(
                                'id',
                                'shift_group_id',
                                'code',
                                'name',
                                'in_time',
                                'out_time',
                                'finger_in_less',
                                'finger_in_more',
                                'finger_out_less',
                                'finger_out_more',
                                'night_shift',
                                'active',
                                'user_created_id',
                                'user_updated_id',
                            )->with('shiftGroup:id,name');
                        },
                        'userExchange' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'userCreate' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'userUpdate' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'userSetup' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'leave' => function ($query) {
                            $query->select(
                                'id',
                                'employee_id',
                                'leave_type_id',
                                'from_date',
                                'to_date',
                                'duration',
                                'note',
                                'leave_status_id',
                            )->with(
                                'employee:id,name',
                                'leaveType:id,name,is_salary_deduction,active',
                                'leaveStatus:id,name',
                                'leaveHistory:id,leave_id,description,comment,created_at,updated_at',
                            );
                        },
                    ])
                    ->select($this->field);
        $query->where('employee_id', $user->employee->id);
        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }
        return $query->orderBy('date', 'ASC')->paginate($perPage);
    }

    public function storeMultiple(array $data)
    {
        return $this->model->insert($data);
    }

    public function updateShiftSchedulesForLeave($employeeId, $fromDate, $toDate, $leaveId, $leaveNote)
    {
        return $this->model
                    ->where('employee_id', $employeeId)
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->update([
                        'leave_id' => $leaveId,
                        'leave_note' => $leaveNote,
                    ]);
    }

    public function deleteByLeaveId($employeeId, $leaveId)
    {
        return $this->model
                    ->where('employee_id', $employeeId)
                    ->where('leave_id', $leaveId)
                    ->update(['leave_id' => null]);
    }

    public function shiftSchedulesExist($employeeId, $fromDate, $toDate)
    {
        return $this->model
            ->where('employee_id', $employeeId)
            ->where('date', '>=', $fromDate)
            ->where('date', '<=', $toDate)
            ->exists();
    }

    public function shiftScheduleEmployeeToday($employeeId)
    {
        $employee = Employee::where('employment_number', $employeeId)->first();
        if (!$employee) {
            return [];
        }
        $shiftschedule = $this->model
                            ->select([
                                'id',
                                'employee_id',
                                'shift_id',
                                DB::raw("TO_CHAR(date, 'YYYY-MM-DD') as date"), // Include the formatted date
                                DB::raw("TO_CHAR(date, 'TMDay') as day_name"),
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
                                'leave_id',
                            ])
                            ->with([
                                'employee' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'generateAbsen' => function ($query) {
                                    $query->select('id', 'period', 'date', DB::raw("TO_CHAR(date, 'TMDay') as day_name"), 'employee_id', 'shift_id', 'date_in_at', 'time_in_at',
                                        'date_out_at', 'time_out_at', 'schedule_date_in_at', 'schedule_time_in_at',
                                        'schedule_date_out_at', 'schedule_time_out_at', 'telat', 'pa', 'holiday',
                                        'night', 'national_holiday', 'note', 'leave_id', 'leave_type_id', 'leave_time_at',
                                        'leave_out_at', 'schedule_leave_time_at', 'schedule_leave_out_at', 'overtime_id',
                                        'overtime_at', 'overtime_time_at', 'overtime_out_at', 'schedule_overtime_time_at',
                                        'schedule_overtime_out_at'
                                    );
                                },
                                'overtime' => function ($query) {
                                    $query->select(
                                        'id',
                                        'from_date',
                                    );
                                },
                                'shift' => function ($query) {
                                    $query->select(
                                        'id',
                                        'shift_group_id',
                                        'code',
                                        'name',
                                        'in_time',
                                        'out_time',
                                        'finger_in_less',
                                        'finger_in_more',
                                        'finger_out_less',
                                        'finger_out_more',
                                        'night_shift',
                                        'active',
                                        'user_created_id',
                                        'user_updated_id',
                                    );
                                },
                                // 'shiftExcange' => function ($query) {
                                //     $query->select(
                                //         'id',
                                //         'employee_id',
                                //         'leave_type_id',
                                //         'from_date',
                                //         'to_date',
                                //         'duration',
                                //         'note',
                                //         'leave_status_id',
                                //     );
                                // },
                                'userExchange' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'userCreate' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'userUpdate' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'userSetup' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'leave' => function ($query) {
                                    $query->select(
                                        'id',
                                        'employee_id',
                                        'leave_type_id',
                                        'from_date',
                                        'to_date',
                                        'duration',
                                        'note',
                                        'leave_status_id',
                                    )->with([
                                        'leaveType:id,name',
                                        'leaveStatus:id,name'
                                    ]);
                                },
                            ])
                        ->where('employee_id', $employee->id)
                        ->where('date', Carbon::today())
                        ->first();
        return $shiftschedule ? $shiftschedule : $shiftschedule = null;
    }

    public function shiftScheduleEmployeeMobile($employeeId)
    {
        $employee = Employee::where('employment_number', $employeeId)->first();
        if (!$employee) {
            return [];
        }
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        // $shiftschedule = ShiftSchedule::select([
        //                         'id',
        //                         'employee_id',
        //                         'shift_id',
        //                         DB::raw("TO_CHAR(date, 'YYYY-MM-DD') as date"), // Include the formatted date
        //                         DB::raw("TO_CHAR(date, 'TMDay') as day_name"),
        //                         'time_in',
        //                         'time_out',
        //                         'late_note',
        //                         'shift_exchange_id',
        //                         'user_exchange_id',
        //                         'user_exchange_at',
        //                         'created_user_id',
        //                         'updated_user_id',
        //                         'setup_user_id',
        //                         'setup_at',
        //                         'period',
        //                         'leave_note',
        //                         'holiday',
        //                         'night',
        //                         'national_holiday',
        //                         'leave_id',
        //                     ])
        //                     ->with([
        //                         'employee' => function ($query) {
        //                             $query->select('id', 'name');
        //                         },
        //                         'generateAbsen' => function ($query) {
        //                             $query->select('id', 'period', 'date', DB::raw("TO_CHAR(date, 'TMDay') as day_name"), 'employee_id', 'shift_id', 'date_in_at', 'time_in_at',
        //                                 'date_out_at', 'time_out_at', 'schedule_date_in_at', 'schedule_time_in_at',
        //                                 'schedule_date_out_at', 'schedule_time_out_at', 'telat', 'pa', 'holiday',
        //                                 'night', 'national_holiday', 'note', 'leave_id', 'leave_type_id', 'leave_time_at',
        //                                 'leave_out_at', 'schedule_leave_time_at', 'schedule_leave_out_at', 'overtime_id',
        //                                 'overtime_at', 'overtime_time_at', 'overtime_out_at', 'schedule_overtime_time_at',
        //                                 'schedule_overtime_out_at'
        //                             );
        //                         },
        //                         'shift' => function ($query) {
        //                             $query->select(
        //                                 'id',
        //                                 'shift_group_id',
        //                                 'code',
        //                                 'name',
        //                                 'in_time',
        //                                 'out_time',
        //                                 'finger_in_less',
        //                                 'finger_in_more',
        //                                 'finger_out_less',
        //                                 'finger_out_more',
        //                                 'night_shift',
        //                                 'active',
        //                                 'user_created_id',
        //                                 'user_updated_id',
        //                             );
        //                         },
        //                         'leave' => function ($query) {
        //                             $query->select(
        //                                 'id',
        //                                 'employee_id',
        //                                 'leave_type_id',
        //                                 'from_date',
        //                                 'to_date',
        //                                 'duration',
        //                                 'note',
        //                                 'leave_status_id',
        //                             )->with([
        //                                 'leaveType:id,name',
        //                                 'leaveStatus:id,name'
        //                             ]);
        //                         },
        //                     ])
        //                     ->where('employee_id', $employee->id)
        //                     ->whereBetween('date', [$startOfMonth, $endOfMonth])
        //                     ->orderBy('date', 'ASC')
        //                     ->get();
        $shiftschedule = DB::table('shift_schedules')
                            ->select([
                                DB::raw("COALESCE(shift_schedules.id, '') as id"),
                                DB::raw("COALESCE(shift_schedules.employee_id, '') as employee_id"),
                                DB::raw("COALESCE(shift_schedules.shift_id, '') as shift_id"),
                                DB::raw("COALESCE(TO_CHAR(shift_schedules.date, 'YYYY-MM-DD'), '') as date"),
                                DB::raw("COALESCE(TO_CHAR(shift_schedules.date, 'TMDay'), '') as day_name"),
                                // DB::raw("COALESCE(shift_schedules.time_in, '') as time_in"),
                                // DB::raw("COALESCE(shift_schedules.time_out, '') as time_out"),
                                DB::raw("COALESCE(shift_schedules.late_note, '') as late_note"),
                                DB::raw("COALESCE(shift_schedules.shift_exchange_id, '') as shift_exchange_id"),
                                // DB::raw("COALESCE(shift_schedules.user_exchange_id, '') as user_exchange_id"),
                                // DB::raw("COALESCE(shift_schedules.user_exchange_at, '') as user_exchange_at"),
                                // DB::raw("COALESCE(shift_schedules.created_user_id, '') as created_user_id"),
                                // DB::raw("COALESCE(shift_schedules.updated_user_id, '') as updated_user_id"),
                                // DB::raw("COALESCE(shift_schedules.setup_user_id, '') as setup_user_id"),
                                // DB::raw("COALESCE(shift_schedules.setup_at, '') as setup_at"),
                                // DB::raw("COALESCE(shift_schedules.period, '') as period"),
                                // DB::raw("COALESCE(shift_schedules.leave_note, '') as leave_note"),
                                // DB::raw("COALESCE(shift_schedules.holiday, '') as holiday"),
                                // DB::raw("COALESCE(shift_schedules.night, '') as night"),
                                // DB::raw("COALESCE(shift_schedules.national_holiday, '') as national_holiday"),
                                // DB::raw("COALESCE(shift_schedules.leave_id, '') as leave_id"),
                                DB::raw("COALESCE(employees.id, '') as employee_id"),
                                DB::raw("COALESCE(employees.name, '') as employee_name"),
                                // DB::raw("COALESCE(generate_absen.id, '') as generate_absen_id"),
                                DB::raw("COALESCE(generate_absen.period, '') as generate_absen_period"),
                                DB::raw("COALESCE(generate_absen.date, '') as generate_absen_date"),
                                DB::raw("COALESCE(shifts.id, '') as shift_id"),
                                DB::raw("COALESCE(shifts.shift_group_id, '') as shift_group_id"),
                                DB::raw("COALESCE(shifts.code, '') as shift_code"),
                                DB::raw("COALESCE(shifts.name, '') as shift_name"),
                                DB::raw("COALESCE(shifts.in_time, '') as in_time"),
                                DB::raw("COALESCE(shifts.out_time, '') as out_time"),
                                DB::raw("COALESCE(shifts.finger_in_less, '') as finger_in_less"),
                                DB::raw("COALESCE(shifts.finger_in_more, '') as finger_in_more"),
                                DB::raw("COALESCE(shifts.finger_out_less, '') as finger_out_less"),
                                DB::raw("COALESCE(shifts.finger_out_more, '') as finger_out_more"),
                                DB::raw("COALESCE(shifts.user_created_id, '') as user_created_id"),
                                DB::raw("COALESCE(shifts.user_updated_id, '') as user_updated_id"),
                                DB::raw("COALESCE(leaves.id, '') as leave_id"),
                                DB::raw("COALESCE(leaves.employee_id, '') as leave_employee_id"),
                                DB::raw("COALESCE(leaves.leave_type_id, '') as leave_type_id"),
                                DB::raw("COALESCE(leaves.from_date, '') as leave_from_date"),
                                DB::raw("COALESCE(leaves.to_date, '') as leave_to_date"),
                                DB::raw("COALESCE(leaves.duration, '') as duration"),
                                DB::raw("COALESCE(leaves.note, '') as note"),
                                DB::raw("COALESCE(leaves.leave_status_id, '') as leave_status_id"),
                                DB::raw("COALESCE(leave_types.name, '') as leave_type_name"),
                                DB::raw("COALESCE(leave_statuses.name, '') as leave_status_name"),
                            ])
                            ->leftJoin('employees', 'shift_schedules.employee_id', '=', 'employees.id')
                            ->leftJoin('generate_absen', 'shift_schedules.date', '=', 'generate_absen.date')
                            ->leftJoin('shifts', 'shift_schedules.shift_id', '=', 'shifts.id')
                            ->leftJoin('leaves', 'shift_schedules.leave_id', '=', 'leaves.id')
                            ->leftJoin('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
                            ->leftJoin('leave_statuses', 'leaves.leave_status_id', '=', 'leave_statuses.id')
                            ->where('shift_schedules.employee_id', $employee->id)
                            ->whereBetween('shift_schedules.date', [$startOfMonth, $endOfMonth])
                            ->orderBy('shift_schedules.date', 'ASC')
                            ->get();

        return $shiftschedule ? $shiftschedule : $shiftschedule = null;
    }
}
