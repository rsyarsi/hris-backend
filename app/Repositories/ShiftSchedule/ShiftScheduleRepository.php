<?php

namespace App\Repositories\ShiftSchedule;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
use Illuminate\Support\Facades\DB;
use App\Services\Employee\EmployeeServiceInterface;
use App\Models\{Shift, ShiftSchedule, Employee, GenerateAbsen};
use App\Repositories\ShiftSchedule\ShiftScheduleRepositoryInterface;

class ShiftScheduleRepository implements ShiftScheduleRepositoryInterface
{
    private $model;
    private $employeeService;
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
        'absen_type',
        'import'
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

    public function __construct(
        ShiftSchedule $model,
        EmployeeServiceInterface $employeeService,
    )
    {
        $this->model = $model;
        $this->employeeService = $employeeService;
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

    public function shiftScheduleSubordinate($perPage, $search = null, $startDate = null, $endDate = null)
    {
        // $startOfMonth = Carbon::now()->startOfMonth();
        // $endOfMonth = Carbon::now()->endOfMonth();
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }
        $queryEmployee = Employee::where(function ($q) use ($user) {
                            $q->where('supervisor_id', $user->employee->id)
                                ->orWhere('manager_id', $user->employee->id)
                                ->orWhere('kabag_id', $user->employee->id);
                        })
                        // ->whereNull('resigned_at')
                        ->get();
        $employeeIds = []; // Collect employee IDs in an array
        foreach ($queryEmployee as $item) {
            $employeeIds[] = $item->id;
        }

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
                    ->whereIn('employee_id', $employeeIds)
                    // ->whereBetween('date', [$startOfMonth, $endOfMonth])
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
                                        'id',
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
        // employee
        $employee = $this->employeeService->show($data['employee_id']);
        // Parse the start_date and end_date
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $shiftSchedules = [];

        $shiftLibur = Shift::where('shift_group_id', $employee->shift_group_id)
                            ->where('code', 'L')
                            ->orWhere('name', 'LIBUR')
                            ->first();

        // Loop through the date range
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $ulid = Ulid::generate(); // Generate a ULID
            $shiftScheduleData = [
                'id' => Str::lower($ulid),
                'employee_id' => $data['employee_id'],
                'shift_id' => null,
                'date' => $date->format('Y-m-d'),
                'time_in' => null,
                'time_out' => null,
                'late_note' => null,
                'shift_exchange_id' => null,
                'user_exchange_id' => null,
                'user_exchange_at' => null,
                'created_user_id' => auth()->id(),
                'updated_user_id' => null, // You may need to set this as per your requirements
                'setup_user_id' => auth()->id(),
                'setup_at' => now(), // You can customize the setup_at value
                'period' => $data['period'],
                'leave_note' => null,
                'holiday' => $date->isWeekend() ? 1 : 0,
                'night' => null,
                'national_holiday' => $data['national_holiday'],
                'absen_type' => 'ABSEN',
                'import' => 0,
            ];

            // Save the ShiftSchedule and get the instance
            $shiftSchedule = $this->model->create($shiftScheduleData);

            $existingEntryGenerateAbsen = GenerateAbsen::where([
                'employee_id' => $data['employee_id'],
                'shift_id' => $shiftLibur->id,
                'date' => $date,
            ])->first();
            if ($existingEntryGenerateAbsen) {
                return null; // Skip this row
            } else if ($date->isWeekend()) { // if sunday
                $data['period'] = $data['period'];
                $data['date'] = $date->format('Y-m-d');
                $data['day'] = $date->format('l');
                $data['employee_id'] = $data['employee_id'];
                $data['employment_id'] = $employee->employment_number;
                $data['shift_id'] = $shiftLibur->id;
                $data['date_in_at'] = $date->format('Y-m-d');
                $data['time_in_at'] = '';
                $data['date_out_at'] = $date->format('Y-m-d');
                $data['time_out_at'] = '';
                $data['schedule_date_in_at'] = $date->format('Y-m-d');
                $data['schedule_time_in_at'] = '';
                $data['schedule_date_out_at'] = $date->format('Y-m-d');
                $data['schedule_time_out_at'] = '';
                $data['holiday'] = 1;
                $data['night'] = 0;
                $data['national_holiday'] = 0;
                $data['type'] = '';
                $data['function'] = '';
                $data['note'] = 'LIBUR';
                $data['type'] = 'ABSEN';
                $data['shift_schedule_id'] = $shiftSchedule->id;
                GenerateAbsen::create($data);
            }
            $shiftSchedules[] = $shiftSchedule; // Append the created instance to the array
        }
        return $shiftSchedules;
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

    public function shiftScheduleKehadiranEmployee($perPage, $startDate = null, $endDate = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }
        $lembur = DB::table('overtimes')
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
                        DB::raw("COALESCE(employees.name, '') as employee_name"),
                        DB::raw("COALESCE(generate_absen.id::text, '') as generate_absen_id"),
                        DB::raw("COALESCE(generate_absen.period, '') as generate_absen_period"),
                        DB::raw("COALESCE(TO_CHAR(generate_absen.date, 'YYYY-MM-DD'), '') as generate_absen_date"),
                        DB::raw("COALESCE(generate_absen.type, '') as generate_absen_type"),
                        DB::raw("COALESCE(generate_absen.time_in_at, '') as generate_absen_time_in_at"),
                        DB::raw("COALESCE(generate_absen.time_out_at, '') as generate_absen_time_out_at"),
                        DB::raw("COALESCE(generate_absen.telat::text, '') as generate_absen_telat"),
                        DB::raw("COALESCE(generate_absen.pa::text, '') as generate_absen_pa"),
                        DB::raw("'' as shift_group_id"),
                        DB::raw("'' as shift_code"),
                        DB::raw("'' as shift_name"),
                        DB::raw("COALESCE(TO_CHAR(overtimes.from_date, 'HH24:MI:SS'), '') as in_time"),
                        DB::raw("COALESCE(TO_CHAR(overtimes.to_date, 'HH24:MI:SS'), '') as out_time"),
                        DB::raw("'60' as finger_in_less"),
                        DB::raw("'60' as finger_in_more"),
                        DB::raw("'60' as finger_out_less"),
                        DB::raw("'60' as finger_out_more"),
                        DB::raw("'1' as user_created_id"),
                        DB::raw("'1' as user_updated_id"),
                        DB::raw("COALESCE(shift_schedules.leave_id::text, '') as leave_id"),
                        DB::raw("'' as leave_employee_id"),
                        DB::raw("'' as leave_type_id"),
                        DB::raw("'' as leave_from_date"),
                        DB::raw("'' as leave_to_date"),
                        DB::raw("'' as leave_duration"),
                        DB::raw("'' as leave_note"),
                        DB::raw("'' as leave_status_id"),
                        DB::raw("'' as leave_type_name"),
                        DB::raw("'' as leave_status_name"),
                        DB::raw("COALESCE('SPL', '') as schedule_type"),
                        DB::raw("COALESCE(overtimes.id, '') as overtime_id")
                    ])
                    ->leftJoin('employees', 'overtimes.employee_id', '=', 'employees.id')
                    // ->leftJoin('generate_absen', function ($join) use ($startOfMonth) {
                    //     $join->on('overtimes.employee_id', '=', 'generate_absen.employee_id')
                    //         ->where(DB::raw("CAST(overtimes.from_date AS DATE)"), '=', DB::raw("CAST(generate_absen.date_in_at AS DATE)"))
                    //         ->where('generate_absen.type', 'SPL');
                    // })
                    // ->leftJoin('shift_schedules', function ($join) use ($startOfMonth) {
                    //     $join->on('overtimes.employee_id', '=', 'shift_schedules.employee_id')
                    //         ->where(DB::raw("CAST(overtimes.from_date AS DATE)"), '=', DB::raw("CAST(shift_schedules.date AS DATE)"));
                    // })
                    ->where('overtimes.employee_id', $user->employee->id);
        $query = $this->model
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

    public function shiftScheduleEmployeeToday($employeeId)
    {
        $employee = Employee::where('employment_number', $employeeId)->first();
        if (!$employee) {
            return [];
        }

        $datwa = Carbon::now()->toDateString();
        // $datwa = '2024-01-31';
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
            $dataShiftSchedule['import'] = 0;
            $createSdhiftSchedule = $this->model->create($dataShiftSchedule);
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
            $dataGenerateAbsen['shift_schedule_id'] = $createSdhiftSchedule->id;
            $dataGenerateAbsen['type'] = $createSdhiftSchedule->absen_type;
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
                        DB::raw("COALESCE(employees.name, '') as employee_name"),
                        DB::raw("COALESCE(generate_absen.id::text, '') as generate_absen_id"),
                        DB::raw("COALESCE(generate_absen.period, '') as generate_absen_period"),
                        DB::raw("COALESCE(TO_CHAR(generate_absen.date, 'YYYY-MM-DD'), '') as generate_absen_date"),
                        DB::raw("COALESCE(generate_absen.type, '') as generate_absen_type"),
                        DB::raw("COALESCE(generate_absen.time_in_at, '') as generate_absen_time_in_at"),
                        DB::raw("COALESCE(generate_absen.time_out_at, '') as generate_absen_time_out_at"),
                        DB::raw("COALESCE(generate_absen.telat::text, '') as generate_absen_telat"),
                        DB::raw("COALESCE(generate_absen.pa::text, '') as generate_absen_pa"),
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
                        // DB::raw("'' as leave_id"),
                        DB::raw("'-' as leave_id"),
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
                    ->leftJoin('employees', 'overtimes.employee_id', '=', 'employees.id')
                    ->leftJoin('generate_absen', function ($join) use ($datwa) {
                        $join->on('overtimes.employee_id', '=', 'generate_absen.employee_id')
                            ->where(DB::raw("CAST(overtimes.from_date AS DATE)"), '=', DB::raw("CAST(generate_absen.date_in_at AS DATE)"))
                            ->where('generate_absen.type', 'SPL');
                    })
                    ->leftJoin('shift_schedules', function ($join) use ($datwa) {
                        $join->on('overtimes.employee_id', '=', 'shift_schedules.employee_id')
                            ->whereRaw("'$datwa' BETWEEN CAST(overtimes.from_date AS DATE) AND CAST(overtimes.to_date AS DATE)")
                            ->whereRaw("'$datwa' BETWEEN CAST(shift_schedules.time_in AS DATE) AND CAST(shift_schedules.time_out AS DATE)");
                    })
                    ->where('shift_schedules.employee_id', $employee->id)
                    ->whereNotIn('overtimes.overtime_status_id', [6,7,8,9,10]);
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
                                DB::raw("COALESCE(shift_schedules.leave_id::text, '') as leave_id"),
                                // DB::raw("COALESCE(leaves.id::text, '') as leave_id"),
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
                            ->leftJoin('generate_absen', function ($join) {
                                $join->on('shift_schedules.employee_id', '=', 'generate_absen.employee_id')
                                    ->where(DB::raw("CAST(shift_schedules.date AS DATE)"), '=', DB::raw("CAST(generate_absen.date AS DATE)"))
                                    ->where('generate_absen.type', 'ABSEN');
                            })
                            ->leftJoin('shifts', 'shift_schedules.shift_id', '=', 'shifts.id')
                            ->leftJoin('leaves', 'shift_schedules.leave_id', '=', 'leaves.id', 'shift_schedules.employee_id','=','leaves.employee_id')
                            ->leftJoin('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
                            ->leftJoin('leave_statuses', 'leaves.leave_status_id', '=', 'leave_statuses.id')
                            // ->whereNotIn('leaves.leave_status_id', [6,7,8,9,10])
                            ->where('shift_schedules.employee_id', $employee->id)
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
        // $startOfMonth = '2024-01-01';
        // $endOfMonth = '2024-01-05';

        $lembur = DB::table('overtimes')
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
                        DB::raw("COALESCE(employees.name, '') as employee_name"),
                        DB::raw("COALESCE(generate_absen.id::text, '') as generate_absen_id"),
                        DB::raw("COALESCE(generate_absen.period, '') as generate_absen_period"),
                        DB::raw("COALESCE(TO_CHAR(generate_absen.date, 'YYYY-MM-DD'), '') as generate_absen_date"),
                        DB::raw("COALESCE(generate_absen.type, '') as generate_absen_type"),
                        DB::raw("COALESCE(generate_absen.time_in_at, '') as generate_absen_time_in_at"),
                        DB::raw("COALESCE(generate_absen.time_out_at, '') as generate_absen_time_out_at"),
                        DB::raw("COALESCE(generate_absen.telat::text, '') as generate_absen_telat"),
                        DB::raw("COALESCE(generate_absen.pa::text, '') as generate_absen_pa"),
                        DB::raw("'' as shift_group_id"),
                        DB::raw("'' as shift_code"),
                        DB::raw("'' as shift_name"),
                        DB::raw("COALESCE(TO_CHAR(overtimes.from_date, 'HH24:MI:SS'), '') as in_time"),
                        DB::raw("COALESCE(TO_CHAR(overtimes.to_date, 'HH24:MI:SS'), '') as out_time"),
                        DB::raw("'60' as finger_in_less"),
                        DB::raw("'60' as finger_in_more"),
                        DB::raw("'60' as finger_out_less"),
                        DB::raw("'60' as finger_out_more"),
                        DB::raw("'1' as user_created_id"),
                        DB::raw("'1' as user_updated_id"),
                        DB::raw("COALESCE(shift_schedules.leave_id::text, '') as leave_id"),
                        DB::raw("'' as leave_employee_id"),
                        DB::raw("'' as leave_type_id"),
                        DB::raw("'' as leave_from_date"),
                        DB::raw("'' as leave_to_date"),
                        DB::raw("'' as leave_duration"),
                        DB::raw("'' as leave_note"),
                        DB::raw("'' as leave_status_id"),
                        DB::raw("'' as leave_type_name"),
                        DB::raw("'' as leave_status_name"),
                        DB::raw("COALESCE('SPL', '') as schedule_type"),
                        DB::raw("COALESCE(overtimes.id, '') as overtime_id")
                    ])
                    ->leftJoin('employees', 'overtimes.employee_id', '=', 'employees.id')
                    ->leftJoin('generate_absen', function ($join) use ($startOfMonth) {
                        $join->on('overtimes.employee_id', '=', 'generate_absen.employee_id')
                            ->where(DB::raw("CAST(overtimes.from_date AS DATE)"), '=', DB::raw("CAST(generate_absen.date_in_at AS DATE)"))
                            ->where('generate_absen.type', 'SPL');
                    })
                    ->leftJoin('shift_schedules', function ($join) use ($startOfMonth) {
                        $join->on('overtimes.employee_id', '=', 'shift_schedules.employee_id')
                            ->where(DB::raw("CAST(overtimes.from_date AS DATE)"), '=', DB::raw("CAST(shift_schedules.date AS DATE)"));
                    })
                    // ->where('overtimes.employee_id', $employee->id)
                    ->where('overtimes.employee_id', $employee->id)
                    ->whereBetween(DB::raw("CAST(overtimes.from_date AS DATE)"), [$startOfMonth, $endOfMonth]);

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
                                DB::raw("COALESCE(shift_schedules.leave_id::text, '') as leave_id"),
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
                            ->leftJoin('generate_absen', function ($join) {
                                $join->on('shift_schedules.employee_id', '=', 'generate_absen.employee_id')
                                    ->where(DB::raw("CAST(shift_schedules.date AS DATE)"), '=', DB::raw("CAST(generate_absen.date AS DATE)"))
                                    ->where('generate_absen.type', 'ABSEN');
                            })
                            ->leftJoin('shifts', 'shift_schedules.shift_id', '=', 'shifts.id')
                            ->leftJoin('leaves', 'shift_schedules.leave_id', '=', 'leaves.id')
                            ->leftJoin('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
                            ->leftJoin('leave_statuses', 'leaves.leave_status_id', '=', 'leave_statuses.id')
                            ->where('shift_schedules.employee_id', $employee->id)
                            ->whereBetween('shift_schedules.date', [$startOfMonth, $endOfMonth])
                            ->unionAll($lembur)
                            ->orderBy('date', 'ASC')
                            ->get();
        return $shiftschedule;
    }

    public function shiftScheduleEmployeeDate($employeeId, $date)
    {
        $shiftSchedul = $this->model
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

    public function generateShiftScheduleNonShift()
    {
        $employees = $this->employeeService->employeeNonShift();
        $shift = Shift::where('shift_group_id', '01hfhe3aqcbw9r1fxvr2j2tb75')
                        ->where('code', 'N')
                        ->first();

        foreach ($employees as $employee) {
            // Get the current month's start and end dates
            $date = Carbon::now();
            // $date = Carbon::parse('2024-01-28');
            // Check if a record already exists for this employee and date
            $existingRecord = ShiftSchedule::where('employee_id', $employee->id)
                                            ->where('date', $date->format('Y-m-d'))
                                            ->first();
            if (!$existingRecord){
                $ulid = Ulid::generate(); // Generate a ULID
                $shiftScheduleData = [
                    'id' => Str::lower($ulid),
                    'employee_id' => $employee->id,
                    'shift_id' => $shift->id,
                    'date' => $date->format('Y-m-d'),
                    'time_in' => $date->format('Y-m-d') . ' ' . $shift->in_time,
                    'time_out' => $date->format('Y-m-d') . ' '. $shift->out_time,
                    'late_note' => null,
                    'shift_exchange_id' => null,
                    'user_exchange_id' => null,
                    'user_exchange_at' => null,
                    'created_user_id' => null,
                    'updated_user_id' => null,
                    'setup_user_id' => null,
                    'setup_at' => now(),
                    'period' => $date->format('Y-m'),
                    'leave_note' => null,
                    'holiday' => $date->isWeekend() ?? 0,
                    'night' => 0,
                    'national_holiday' => 0,
                    'absen_type' => 'ABSEN',
                ];
                $shiftScheduleCreate = $this->model->create($shiftScheduleData);

                $existingEntryGenerateAbsen = GenerateAbsen::where([
                    'employee_id' => $employee->id,
                    'shift_id' => $shift->id,
                    'date' => $date,
                ])->first();

                if ($existingEntryGenerateAbsen) {
                    return null; // Skip this row
                } else if ($date->isWeekend()) { // if sunday
                    $data['period'] = $date->format('Y-m');
                    $data['date'] = $date->format('Y-m-d');
                    $data['day'] = $date->format('l');
                    $data['employee_id'] = $employee->id;
                    $data['employment_id'] = $employee->employment_number;
                    $data['shift_id'] = $shift->id;
                    $data['date_in_at'] = $date->format('Y-m-d');
                    $data['time_in_at'] = '';
                    $data['date_out_at'] = $date->format('Y-m-d');
                    $data['time_out_at'] = '';
                    $data['schedule_date_in_at'] = $date->format('Y-m-d');
                    $data['schedule_time_in_at'] = '';
                    $data['schedule_date_out_at'] = $date->format('Y-m-d');
                    $data['schedule_time_out_at'] = '';
                    $data['holiday'] = 1;
                    $data['night'] = 0;
                    $data['national_holiday'] = 0;
                    $data['type'] = '';
                    $data['function'] = '';
                    $data['note'] = 'LIBUR';
                    $data['shift_schedule_id'] = $shiftScheduleCreate->id;
                    GenerateAbsen::create($data);
                }
            }
        }
        return [
            'message' => 'Generate Abesen Non Shift successfully!',
            'success' => 'true',
            'code' => 200,
            'data' => 'Generate Abesen Non Shift successfully!',
        ];
    }
}
