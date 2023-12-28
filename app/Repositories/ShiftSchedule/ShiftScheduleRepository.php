<?php

namespace App\Repositories\ShiftSchedule;

use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Employee;
use App\Models\GenerateAbsen;
use App\Models\ShiftGroup;
use App\Models\ShiftSchedule;
use Illuminate\Support\Facades\DB;
use App\Repositories\ShiftSchedule\ShiftScheduleRepositoryInterface;

class ShiftScheduleRepository implements ShiftScheduleRepositoryInterface
{
    private $model;
    private $field =
    [
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
        'leave_id',
        'absen_type'
    ];
    private $fieldShiftScheduleExchange =
    [
        'id',
        'employe_requested_id',
        'shift_schedule_date_requested',
        'shift_schedule_request_id',
        'shift_schedule_code_requested',
        'shift_schedule_name_requested',
        'shift_schedule_time_from_requested',
        'shift_schedule_time_end_requested',
        'shift_exchange_type',
        'to_employee_id',
        'to_shift_schedule_date',
        'to_shift_schedule_id',
        'to_shift_schedule_code',
        'to_shift_schedule_name',
        'to_shift_schedule_time_from',
        'to_shift_schedule_time_end',
        'exchange_employee_id',
        'exchange_date',
        'exchange_shift_schedule_id',
        'exchange_shift_schedule_code',
        'exchange_shift_schedule_name',
        'exchange_shift_schedule_time_from',
        'exchange_shift_schedule_time_end',
        'date_created',
        'date_updated',
        'user_created_id',
        'user_updated_id',
        'cancel',
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
                            $query->select('id', 'name', 'employment_number');
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
                        'shiftExchange' => function ($query) {
                            $query->select($this->fieldShiftScheduleExchange);
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
                                            $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
                                                            ->orWhere('employment_number', 'like', '%' . $search . '%');
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
                                    $query->select('id', 'name', 'employment_number');
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
                                'shiftExchange' => function ($query) {
                                    $query->select($this->fieldShiftScheduleExchange);
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
            $data['updated_user_id'] = auth()->id();
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
        $oldData = $this->model
                        ->where('leave_id', $leaveId)
                        ->first();

        if ($oldData) {
            $this->model->where('id', $oldData->id)
                        ->update([
                            'leave_id' => null,
                            'leave_note' => null,
                        ]);
        }

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
                    ->update([
                        'leave_id' => null,
                        'leave_note' => null
                    ]);
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
        $datwa = Carbon::now()->toDateString();

        // check shift group id apakah Non Shift atau tidak
        $nonShiftGroupId = '01hfhe3aqcbw9r1fxvr2j2tb75';
        // check di table shift schedule exists ?
        $checkShiftSchedule = DB::table('shift_schedules')
                                ->select('shift_schedules.*')
                                ->where('shift_schedules.employee_id', $employee->id)
                                ->where('shift_schedules.date', $datwa)
                                ->first();

        $shift = Shift::where('shift_group_id', $nonShiftGroupId)
                        ->where('code', 'N')
                        ->orWhere('name', 'NON SHIFT')
                        ->first();
        // return $employee->shift_group_id;
        if ($employee->shift_group_id == $nonShiftGroupId && $checkShiftSchedule == null) {
            // insert data ke table shift schedule
            $dataShiftSchedule['employee_id'] = $employee->id;
            $dataShiftSchedule['shift_id'] = $shift->id;
            $dataShiftSchedule['date'] = $datwa;
            $dataShiftSchedule['created_user_id'] = auth()->id();
            $dataShiftSchedule['setup_user_id'] = auth()->id();
            $dataShiftSchedule['setup_at'] = now();
            $dataShiftSchedule['time_in'] = $datwa . ' ' . $shift->in_time;
            $dataShiftSchedule['time_out'] = $datwa . ' ' . $shift->out_time;
            $dataShiftSchedule['period'] = now()->format('Y-m');
            $dataShiftSchedule['holiday'] = 0;
            $dataShiftSchedule['night'] = 0;
            $dataShiftSchedule['national_holiday'] = 0;
            $dataShiftSchedule['absen_type'] = 'ABSEN';
            $this->model->create($dataShiftSchedule);
            // insert data ke generate absen
            $dataGenerateAbsen['period'] = now()->format('Y-m');
            $dataGenerateAbsen['date'] = $datwa;
            $dataGenerateAbsen['schedule_date_in_at'] = $datwa;
            $dataGenerateAbsen['schedule_time_in_at'] = $shift->in_time;
            $dataGenerateAbsen['schedule_date_out_at'] = $datwa;
            $dataGenerateAbsen['schedule_time_out_at'] = $shift->out_time;
            $dataGenerateAbsen['employee_id'] = $employee->id;
            $dataGenerateAbsen['shift_id'] = $shift->id;
            $dataGenerateAbsen['day'] = now()->format('l');
            $dataGenerateAbsen['note'] = 'BELUM ABSEN IN/OUT';
            $dataGenerateAbsen['holiday'] = 0;
            $dataGenerateAbsen['night'] = 0;
            $dataGenerateAbsen['national_holiday'] = 0;
            $dataGenerateAbsen['employment_id'] = $employeeId;
            GenerateAbsen::create($dataGenerateAbsen);
        }

        $lembur = DB::table('overtimes')
                    ->select([
                        DB::raw("COALESCE(shift_schedules.id, '') as id"),
                        DB::raw("COALESCE(overtimes.employee_id, '') as employee_id"),
                        DB::raw("COALESCE(shift_schedules.shift_id, '') as shift_id"),
                        DB::raw("COALESCE(TO_CHAR(overtimes.to_date, 'YYYY-MM-DD'), '') as date"),
                        DB::raw("COALESCE(TO_CHAR(overtimes.from_date, 'TMDay'), '') as day_name"),
                        DB::raw("COALESCE(TO_CHAR(overtimes.from_date, 'YYYY-MM-DD HH24:MI:SS'), '') as time_in"),
                        DB::raw("COALESCE(TO_CHAR(overtimes.to_date, 'YYYY-MM-DD HH24:MI:SS'), '') as time_out"),
                        DB::raw("'' as late_note"),
                        DB::raw("'' as shift_exchange_id"),
                        DB::raw("'' as user_exchange_id"),
                        DB::raw("'' as user_exchange_at"),
                        DB::raw("'1' as created_user_id"),
                        DB::raw("'1' as updated_user_id"),
                        DB::raw("'1' as setup_user_id"),
                        DB::raw("COALESCE(TO_CHAR(overtimes.created_at, 'YYYY-MM-DD HH24:MI:SS'), '') as setup_at"),
                        DB::raw("shift_schedules.period as period"),
                        DB::raw("'' as leave_note"),
                        DB::raw("'0' as holiday"),
                        DB::raw("'0' as night"),
                        DB::raw("'0' as national_holiday"),
                        DB::raw("'-' as leave_id"),
                        DB::raw("COALESCE(employees.name, '') as employee_name"),
                        DB::raw("'' as generate_absen_id"),
                        DB::raw("'' as generate_absen_period"),
                        DB::raw("'' as generate_absen_date"),
                        DB::raw("'' as generate_absen_type"),
                        DB::raw("'' as generate_absen_time_in_at"),
                        DB::raw("'' as generate_absen_time_out_at"),
                        DB::raw("'' as generate_absen_telat"),
                        DB::raw("'' as generate_absen_pa"),
                        DB::raw("'' as shift_group_id"),
                        DB::raw("'' as shift_code"),
                        DB::raw("COALESCE(overtimes.type, '') as shift_name"),
                        DB::raw("COALESCE(TO_CHAR(overtimes.from_date, 'HH24:MI:SS'), '') as in_time"),
                        DB::raw("COALESCE(TO_CHAR(overtimes.to_date, 'HH24:MI:SS'), '') as out_time"),
                        DB::raw("'60' as finger_in_less"),
                        DB::raw("'60' as finger_in_more"),
                        DB::raw("'60' as finger_out_less"),
                        DB::raw("'60' as finger_out_more"),
                        DB::raw("'1' as user_created_id"),
                        DB::raw("'1' as user_updated_id"),
                        DB::raw("'' as leave_id"),
                        DB::raw("'' as leave_employee_id"),
                        DB::raw("'' as leave_type_id"),
                        DB::raw("'' as leave_from_date"),
                        DB::raw("'' as leave_to_date"),
                        DB::raw("'' as leave_duration"),
                        DB::raw("'' as leave_note"),
                        DB::raw("'' as leave_status_id"),
                        DB::raw("'' as leave_type_name"),
                        DB::raw("'' as leave_status_name"),
                        DB::raw("'SPL' AS schedule_type"),
                        DB::raw("overtimes.id as overtime_id")
                    ])
                    ->leftJoin('shift_schedules', DB::raw("CAST(overtimes.from_date AS DATE)"), '=', 'shift_schedules.date', 'overtimes.employee_id','=','shift_schedules.employee_id')
                    ->leftJoin('employees', 'shift_schedules.employee_id', '=', 'employees.id')
                    ->where('shift_schedules.employee_id', $employee->id)
                    ->where('overtimes.overtime_status_id', '5')
                    ->whereRaw("'$datwa' BETWEEN CAST(overtimes.from_date AS DATE) AND CAST(overtimes.to_date AS DATE)");
                    // union all here
        $shiftschedule = DB::table('shift_schedules')
                    ->select([
                        DB::raw("COALESCE(shift_schedules.id, '') as id"),
                        DB::raw("COALESCE(shift_schedules.employee_id, '') as employee_id"),
                        DB::raw("COALESCE(shift_schedules.shift_id, '') as shift_id"),
                        DB::raw("COALESCE(TO_CHAR(shift_schedules.date, 'YYYY-MM-DD'), '') as date"),
                        DB::raw("COALESCE(TO_CHAR(shift_schedules.date, 'TMDay'), '') as day_name"),
                        DB::raw("COALESCE(TO_CHAR(shift_schedules.time_in, 'YYYY-MM-DD HH24:MI:SS'), '') as time_in"),
                        DB::raw("COALESCE(TO_CHAR(shift_schedules.time_out, 'YYYY-MM-DD HH24:MI:SS'), '') as time_out"),
                        DB::raw("COALESCE(shift_schedules.late_note, '') as late_note"),
                        DB::raw("COALESCE(shift_schedules.shift_exchange_id, '') as shift_exchange_id"),
                        DB::raw("COALESCE(shift_schedules.user_exchange_id::text, '') as user_exchange_id"),
                        DB::raw("COALESCE(TO_CHAR(shift_schedules.user_exchange_at, 'YYYY-MM-DD HH24:MI:SS'), '') as user_exchange_at"),
                        DB::raw("COALESCE(shift_schedules.created_user_id::text, '') as created_user_id"),
                        DB::raw("COALESCE(shift_schedules.updated_user_id::text, '') as updated_user_id"),
                        DB::raw("COALESCE(shift_schedules.setup_user_id::text, '') as setup_user_id"),
                        DB::raw("COALESCE(TO_CHAR(shift_schedules.setup_at, 'YYYY-MM-DD HH24:MI:SS'), '') as setup_at"),
                        DB::raw("COALESCE(shift_schedules.period, '') as period"),
                        DB::raw("COALESCE(shift_schedules.leave_note, '') as leave_note"),
                        DB::raw("COALESCE(shift_schedules.holiday::text, '') as holiday"),
                        DB::raw("COALESCE(shift_schedules.night::text, '') as night"),
                        DB::raw("COALESCE(shift_schedules.national_holiday::text, '') as national_holiday"),
                        DB::raw("COALESCE(shift_schedules.leave_id::text, '') as leave_id"),
                        DB::raw("COALESCE(employees.name, '') as employee_name"),
                        DB::raw("COALESCE(generate_absen.id::text, '') as generate_absen_id"),
                        DB::raw("COALESCE(generate_absen.period, '') as generate_absen_period"),
                        DB::raw("COALESCE(TO_CHAR(generate_absen.date, 'YYYY-MM-DD'), '') as generate_absen_date"),
                        DB::raw("COALESCE(generate_absen.type, '') as generate_absen_type"),
                        DB::raw("COALESCE(generate_absen.time_in_at, '') as generate_absen_time_in_at"),
                        DB::raw("COALESCE(generate_absen.time_out_at, '') as generate_absen_time_out_at"),
                        DB::raw("COALESCE(generate_absen.telat::text, '') as generate_absen_telat"),
                        DB::raw("COALESCE(generate_absen.pa::text, '') as generate_absen_pa"),
                        DB::raw("COALESCE(shifts.shift_group_id::text, '') as shift_group_id"),
                        DB::raw("COALESCE(shifts.code, '') as shift_code"),
                        DB::raw("COALESCE(shifts.name, '') as shift_name"),
                        DB::raw("COALESCE(shifts.in_time, '') as in_time"),
                        DB::raw("COALESCE(shifts.out_time, '') as out_time"),
                        DB::raw("COALESCE(shifts.finger_in_less::text, '') as finger_in_less"),
                        DB::raw("COALESCE(shifts.finger_in_more::text, '') as finger_in_more"),
                        DB::raw("COALESCE(shifts.finger_out_less::text, '') as finger_out_less"),
                        DB::raw("COALESCE(shifts.finger_out_more::text, '') as finger_out_more"),
                        DB::raw("COALESCE(shifts.user_created_id::text, '') as user_created_id"),
                        DB::raw("COALESCE(shifts.user_updated_id::text, '') as user_updated_id"),
                        DB::raw("COALESCE(leaves.id::text, '') as leave_id"),
                        DB::raw("COALESCE(leaves.employee_id::text, '') as leave_employee_id"),
                        DB::raw("COALESCE(leaves.leave_type_id::text, '') as leave_type_id"),
                        DB::raw("COALESCE(TO_CHAR(leaves.from_date, 'YYYY-MM-DD'), '') as leave_from_date"),
                        DB::raw("COALESCE(TO_CHAR(leaves.to_date, 'YYYY-MM-DD'), '') as leave_to_date"),
                        DB::raw("COALESCE(leaves.duration::text, '') as leave_duration"),
                        DB::raw("COALESCE(leaves.note, '') as leave_note"),
                        DB::raw("COALESCE(leaves.leave_status_id::text, '') as leave_status_id"),
                        DB::raw("COALESCE(leave_types.name, '') as leave_type_name"),
                        DB::raw("COALESCE(leave_statuses.name, '') as leave_status_name"),
                        DB::raw("'ABSEN' AS schedule_type"),
                        DB::raw("'' as overtime_id")
                    ])
                    ->leftJoin('employees', 'shift_schedules.employee_id', '=', 'employees.id')
                    // ->leftJoin('generate_absen', 'shift_schedules.date', '=', 'generate_absen.date','shift_schedules.employee_id','=','generate_absen.employee_id')
                    ->leftJoin('generate_absen', function ($join) use ($employee) {
                        $join->on('shift_schedules.employee_id', '=', 'generate_absen.employee_id')
                            ->where(DB::raw("CAST(shift_schedules.date AS DATE)"), '=', DB::raw("CAST(generate_absen.date AS DATE)"));
                    })
                    ->leftJoin('shifts', 'shift_schedules.shift_id', '=', 'shifts.id')
                    ->leftJoin('leaves', 'shift_schedules.leave_id', '=', 'leaves.id' ,'shift_schedules.employee_id','=','leaves.employee_id')
                    ->leftJoin('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
                    ->leftJoin('leave_statuses', 'leaves.leave_status_id', '=', 'leave_statuses.id')
                    ->where('shift_schedules.employee_id', $employee->id)
                    // ->where(DB::raw("TO_CHAR(shift_schedules.time_out, 'YYYY-MM-DD')"), $datwa)
                    ->whereRaw("'$datwa' BETWEEN CAST(shift_schedules.time_in AS DATE) AND CAST(shift_schedules.time_out AS DATE)")
                    ->unionAll($lembur)
                    ->orderBy('date', 'ASC')
                    ->get();

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
        $shiftschedule = DB::table('shift_schedules')
                            ->select([
                                DB::raw("COALESCE(shift_schedules.id, '') as id"),
                                DB::raw("COALESCE(shift_schedules.employee_id, '') as employee_id"),
                                DB::raw("COALESCE(shift_schedules.shift_id, '') as shift_id"),
                                DB::raw("COALESCE(TO_CHAR(shift_schedules.date, 'YYYY-MM-DD'), '') as date"),
                                DB::raw("COALESCE(TO_CHAR(shift_schedules.date, 'TMDay'), '') as day_name"),
                                DB::raw("COALESCE(TO_CHAR(shift_schedules.time_in, 'YYYY-MM-DD HH24:MI:SS'), '') as time_in"),
                                DB::raw("COALESCE(TO_CHAR(shift_schedules.time_out, 'YYYY-MM-DD HH24:MI:SS'), '') as time_out"),
                                DB::raw("COALESCE(shift_schedules.late_note, '') as late_note"),
                                DB::raw("COALESCE(shift_schedules.shift_exchange_id, '') as shift_exchange_id"),
                                DB::raw("COALESCE(shift_schedules.user_exchange_id::text, '') as user_exchange_id"),
                                DB::raw("COALESCE(TO_CHAR(shift_schedules.user_exchange_at, 'YYYY-MM-DD HH24:MI:SS'), '') as user_exchange_at"),
                                DB::raw("COALESCE(shift_schedules.created_user_id::text, '') as created_user_id"),
                                DB::raw("COALESCE(shift_schedules.updated_user_id::text, '') as updated_user_id"),
                                DB::raw("COALESCE(shift_schedules.setup_user_id::text, '') as setup_user_id"),
                                DB::raw("COALESCE(TO_CHAR(shift_schedules.setup_at, 'YYYY-MM-DD HH24:MI:SS'), '') as setup_at"),
                                DB::raw("COALESCE(shift_schedules.period, '') as period"),
                                DB::raw("COALESCE(shift_schedules.leave_note, '') as leave_note"),
                                DB::raw("COALESCE(shift_schedules.holiday::text, '') as holiday"),
                                DB::raw("COALESCE(shift_schedules.night::text, '') as night"),
                                DB::raw("COALESCE(shift_schedules.national_holiday::text, '') as national_holiday"),
                                DB::raw("COALESCE(shift_schedules.leave_id::text, '') as leave_id"),
                                // DB::raw("COALESCE(employees.id, '') as employee_id"),
                                DB::raw("COALESCE(employees.name, '') as employee_name"),
                                DB::raw("COALESCE(generate_absen.id::text, '') as generate_absen_id"),
                                DB::raw("COALESCE(generate_absen.period, '') as generate_absen_period"),
                                DB::raw("COALESCE(TO_CHAR(generate_absen.date, 'YYYY-MM-DD'), '') as generate_absen_date"),
                                DB::raw("COALESCE(generate_absen.type, '') as generate_absen_type"),
                                DB::raw("COALESCE(generate_absen.time_in_at, '') as generate_absen_time_in_at"),
                                DB::raw("COALESCE(generate_absen.time_out_at, '') as generate_absen_time_out_at"),
                                DB::raw("COALESCE(generate_absen.telat::text, '') as generate_absen_telat"),
                                DB::raw("COALESCE(generate_absen.pa::text, '') as generate_absen_pa"),
                                // DB::raw("COALESCE(shifts.id::text, '') as shift_id"),
                                DB::raw("COALESCE(shifts.shift_group_id::text, '') as shift_group_id"),
                                DB::raw("COALESCE(shifts.code, '') as shift_code"),
                                DB::raw("COALESCE(shifts.name, '') as shift_name"),
                                DB::raw("COALESCE(shifts.in_time, '') as in_time"),
                                DB::raw("COALESCE(shifts.out_time, '') as out_time"),
                                DB::raw("COALESCE(shifts.finger_in_less::text, '') as finger_in_less"),
                                DB::raw("COALESCE(shifts.finger_in_more::text, '') as finger_in_more"),
                                DB::raw("COALESCE(shifts.finger_out_less::text, '') as finger_out_less"),
                                DB::raw("COALESCE(shifts.finger_out_more::text, '') as finger_out_more"),
                                DB::raw("COALESCE(shifts.finger_out_more::text, '') as finger_out_more"),
                                DB::raw("COALESCE(shifts.user_created_id::text, '') as user_created_id"),
                                DB::raw("COALESCE(shifts.user_updated_id::text, '') as user_updated_id"),
                                DB::raw("COALESCE(leaves.id::text, '') as leave_id"),
                                DB::raw("COALESCE(leaves.employee_id::text, '') as leave_employee_id"),
                                DB::raw("COALESCE(leaves.leave_type_id::text, '') as leave_type_id"),
                                DB::raw("COALESCE(TO_CHAR(leaves.from_date, 'YYYY-MM-DD'), '') as leave_from_date"),
                                DB::raw("COALESCE(TO_CHAR(leaves.to_date, 'YYYY-MM-DD'), '') as leave_to_date"),
                                DB::raw("COALESCE(leaves.duration::text, '') as leave_duration"),
                                DB::raw("COALESCE(leaves.note, '') as leave_note"),
                                DB::raw("COALESCE(leaves.leave_status_id::text, '') as leave_status_id"),
                                DB::raw("COALESCE(leave_types.name, '') as leave_type_name"),
                                DB::raw("COALESCE(leave_statuses.name, '') as leave_status_name"),
                            ])

                            ->leftJoin('employees', 'shift_schedules.employee_id', '=', 'employees.id')
                            // ->leftJoin('generate_absen', function ($join) use ($employee) {
                            //     $join->on('generate_absen.date', '=', 'shift_schedules.date')
                            //          ->where('shift_schedules.employee_id', '=', 'generate_absen.employee_id');
                            // })
                            ->leftJoin('generate_absen', function ($join) use ($employee) {
                                $join->on('shift_schedules.employee_id', '=', 'generate_absen.employee_id')
                                     ->where(DB::raw("CAST(shift_schedules.date AS DATE)"), '=', DB::raw("CAST(generate_absen.date AS DATE)"));
                            })
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

    public function shiftScheduleEmployeeDate($employeeId, $date)
    {
        $shiftSchedul = $this->model
                            ->where('employee_id', $employeeId)
                            ->where('date', $date)
                            ->first($this->field);
        if ($shiftSchedul) {
            return $shiftSchedul;
        }
        return null;
    }

    public function updateShiftScheduleExchage($data)
    {
        $employeeId = $data['employee_id'];
        $date = $data['date_requested'];
        $shiftSchedule = $this->model
                            ->where('employee_id', $employeeId)
                            ->where('date', $date)
                            ->first($this->field);

        $data['shift_exchange_id'] = $data['shift_exchange_id'];
        $data['user_exchange_id'] = $data['user_exchange_id'];
        $data['user_exchange_at'] = now();
        return $shiftSchedule->update($data);
    }
}
