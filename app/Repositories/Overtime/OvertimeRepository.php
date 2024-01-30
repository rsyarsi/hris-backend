<?php

namespace App\Repositories\Overtime;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\{Employee, Overtime, User, ShiftSchedule, GenerateAbsen, OvertimeHistory};
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\Firebase\FirebaseServiceInterface;
use App\Repositories\Overtime\OvertimeRepositoryInterface;
use App\Services\OvertimeStatus\OvertimeStatusServiceInterface;
use App\Services\OvertimeHistory\OvertimeHistoryServiceInterface;

class OvertimeRepository implements OvertimeRepositoryInterface
{
    private $model;
    private $overtimeHistoryService;
    private $overtimeStatusService;
    private $firebaseService;
    private $employeeService;
    private $field = [
        'id',
        'employee_id',
        'task',
        'note',
        'overtime_status_id',
        'from_date',
        'amount',
        'type',
        'to_date',
        'duration',
        'libur',
    ];

    public function __construct(
        Overtime $model,
        FirebaseServiceInterface $firebaseService,
        EmployeeServiceInterface $employeeService,
        OvertimeHistoryServiceInterface $overtimeHistoryService,
        OvertimeStatusServiceInterface $overtimeStatusService
    )
    {
        $this->model = $model;
        $this->firebaseService = $firebaseService;
        $this->employeeService = $employeeService;
        $this->overtimeHistoryService = $overtimeHistoryService;
        $this->overtimeStatusService = $overtimeStatusService;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name', 'employment_number');
                        },
                        'overtimeStatus' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'overtimeHistory' => function ($query) {
                            $query->select(
                                'id',
                                'overtime_id',
                                'user_id',
                                'description',
                                'ip_address',
                                'user_agent',
                                'comment',
                                'libur',
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
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
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
        if ($employee->shift_group_id == $nonShiftGroupId && !$checkShiftSchedule) {
            // insert data ke table shift schedule
            $dataShiftSchedule['employee_id'] = $employee->id;
            $dataShiftSchedule['shift_id'] = $shift->id;
            $dataShiftSchedule['date'] = $fromDate->toDateString();
            $dataShiftSchedule['created_user_id'] = auth()->id();
            $dataShiftSchedule['setup_user_id'] = auth()->id();
            $dataShiftSchedule['setup_at'] = now();
            $dataShiftSchedule['time_in'] = $fromDate->toDateString() . ' ' . $shift->in_time;
            $dataShiftSchedule['time_out'] = $toDate->toDateString() . ' ' . $shift->out_time;
            $dataShiftSchedule['period'] = now()->format('Y-m');
            $dataShiftSchedule['holiday'] = 0;
            $dataShiftSchedule['night'] = 0;
            $dataShiftSchedule['national_holiday'] = 0;
            $dataShiftSchedule['absen_type'] = 'ABSEN';
            $dataShiftSchedule['import'] = 0;
            $checkShiftSchedule = ShiftSchedule::create($dataShiftSchedule);
        }

        if ($employee->shift_group_id !== $nonShiftGroupId && !$checkShiftSchedule) {
            return [
                'message' => 'Validation Error!',
                'error' => true,
                'code' => 422,
                'data' => ['type' => ['Data Shift Schedule belum ada, silahkan hubungi atasan!']]
            ];
        }
        if ($checkShiftSchedule->leave_id !== null) {
            return [
                'message' => 'Validation Error!',
                'error' => true,
                'code' => 422,
                'data' => ['type' => ['Data Shift Schedule sudah tercatat cuti!']]
            ];
        }

        $overtime = $this->model->create($data);
        $overtimeStatus = $this->overtimeStatusService->show($data['overtime_status_id']);
        // save to table overtime history
        $historyData = [
            'overtime_id' => $overtime->id,
            'user_id' => auth()->id(),
            'description' => 'OVERTIME STATUS '. $overtimeStatus->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'comment' => $data['note'],
            'libur' => $data['libur'],
        ];
        $this->overtimeHistoryService->store($historyData);
        // generate absen if type dinas luar
        if ($data['type'] == "DINAS LUAR" || $data['type'] == "DINAS-LUAR" || $data['type'] == "dinas luar" || $data['type'] == "dinas-luar") {
            $fromDateParse = Carbon::parse($data['from_date']);
            $toDateParse = Carbon::parse($data['to_date']);
            // employee
            $employee = Employee::where('id', $data['employee_id'])->first(['id', 'name', 'employment_number', 'shift_group_id']);
            // shift_schedule
            $shiftSchedule = ShiftSchedule::where('employee_id', $data['employee_id'])
                                    ->where('date', $fromDateParse->toDateString())
                                    ->first();

            $data['period'] = $fromDateParse->format('Y-m');
            $data['date'] = $fromDateParse->toDateString();
            $data['day'] = $fromDateParse->format('l');
            $data['employee_id'] = $data['employee_id'];
            $data['employment_id'] = $employee->employment_number;
            $data['shift_id'] = $shiftSchedule->shift_id;
            $data['date_in_at'] = $fromDateParse->toDateString();
            $data['time_in_at'] = $fromDateParse->toTimeString();
            $data['date_out_at'] = $toDateParse->toDateString();
            $data['time_out_at'] = $toDateParse->toTimeString();
            $data['schedule_date_in_at'] = $shiftSchedule->date;
            $data['schedule_time_in_at'] = Carbon::parse($shiftSchedule->time_in)->toTimeString();
            $data['schedule_date_out_at'] = $shiftSchedule->date;
            $data['schedule_time_out_at'] = Carbon::parse($shiftSchedule->time_out)->toTimeString();
            $data['holiday'] = $shiftSchedule->holiday;
            $data['night'] = $shiftSchedule->night;
            $data['national_holiday'] = $shiftSchedule->national_holiday;
            $data['function'] = '';
            $data['note'] = '';
            $data['type'] = 'SPL';
            $data['overtime_type'] = $data['type'];
            $data['overtime_hours'] = $data['duration'];
            $data['shift_schedule_id'] = $shiftSchedule->id;
            GenerateAbsen::create($data);
        }
        // send firebase notification
        $typeSend = 'Overtime';
        $registrationIds = [];
        $employee = $this->employeeService->show($overtime->employee_id);
        $getHakAkses = User::where('username',$employee->employment_number)->get()->first();
        // return $getHakAkses;
        $registrationIds[] = $getHakAkses->firebase_id;

        // notif ke HRDs
        $employeeHrd = User::where('hrd','1')->get();
        foreach ($employeeHrd as $key ) {
            # code...
            $firebaseIdx = $key;
        }

        // if($employee->supervisor != null){
        //     if($employee->supervisor->user != null){
        //         $registrationIds[] = $employee->supervisor->user->firebase_id;
        //     }
        // }
        if($employee->kabag_id != null ){
            if($employee->kabag->user != null){
                $registrationIds[] = $employee->kabag->user->firebase_id;
            }
        }
        if($employee->manager_id != null ){
            if($employee->manager->user != null){
                $registrationIds[] = $employee->manager->user->firebase_id;
            }
        }


        // Check if there are valid registration IDs before sending the notification
        if (!empty($registrationIds)) {
            $this->firebaseService->sendNotification($registrationIds, $typeSend, $employee->name);
        }

        return [
            'message' => 'Overtime created successfully',
            'error' => false,
            'code' => 201,
            'data' => [$overtime]
        ];
    }

    public function overtimeCreateMobile(array $data)
    {
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
        if ($employee->shift_group_id == $nonShiftGroupId && !$checkShiftSchedule) {
            // insert data ke table shift schedule
            $dataShiftSchedule['employee_id'] = $employee->id;
            $dataShiftSchedule['shift_id'] = $shift->id;
            $dataShiftSchedule['date'] = $fromDate->toDateString();
            $dataShiftSchedule['created_user_id'] = auth()->id();
            $dataShiftSchedule['setup_user_id'] = auth()->id();
            $dataShiftSchedule['setup_at'] = now();
            $dataShiftSchedule['time_in'] = $fromDate->toDateString() . ' ' . $shift->in_time;
            $dataShiftSchedule['time_out'] = $toDate->toDateString() . ' ' . $shift->out_time;
            $dataShiftSchedule['period'] = now()->format('Y-m');
            $dataShiftSchedule['holiday'] = 0;
            $dataShiftSchedule['night'] = 0;
            $dataShiftSchedule['national_holiday'] = 0;
            $dataShiftSchedule['absen_type'] = 'ABSEN';
            $dataShiftSchedule['import'] = 0;
            $checkShiftSchedule = ShiftSchedule::create($dataShiftSchedule);
        }

        if ($employee->shift_group_id !== $nonShiftGroupId && !$checkShiftSchedule) {
            return [
                'message' => 'Validation Error!',
                'success' => true,
                'code' => 201,
                'data' => ['type' => ['Data Shift Schedule belum ada, silahkan hubungi atasan!']]
            ];
        }
        if ($checkShiftSchedule->leave_id !== null) {
            return [
                'message' => 'Data Shift Schedule sudah tercatat cuti!',
                'success' => false,
                'code' => 201,
                'data' => ['type' => ['Data Shift Schedule sudah tercatat cuti!']]
            ];
        }

        $overtime = $this->model->create($data);
        $overtimeStatus = $this->overtimeStatusService->show($data['overtime_status_id']);
        // save to table overtime history
        $historyData = [
            'overtime_id' => $overtime->id,
            'user_id' => auth()->id(),
            'description' => 'OVERTIME STATUS '. $overtimeStatus->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'comment' => $data['note'],
            'libur' => $data['libur'],
        ];
        $this->overtimeHistoryService->store($historyData);
        if ($data['type'] == "DINAS LUAR" || $data['type'] == "DINAS-LUAR" || $data['type'] == "dinas luar" || $data['type'] == "dinas-luar") {
            $fromDateParse = Carbon::parse($data['from_date']);
            $toDateParse = Carbon::parse($data['to_date']);
            // employee
            $employee = Employee::where('id', $data['employee_id'])->first(['id', 'name', 'employment_number', 'shift_group_id']);
            // shift_schedule
            $shiftSchedule = ShiftSchedule::where('employee_id', $employeeId)
                                    ->where('date', $fromDateParse->toDateString())
                                    ->first();

            $data['period'] = $fromDateParse->format('Y-m');
            $data['date'] = $fromDateParse->toDateString();
            $data['day'] = $fromDateParse->format('l');
            $data['employee_id'] = $data['employee_id'];
            $data['employment_id'] = $employee->employment_number;
            $data['shift_id'] = $shiftSchedule->shift_id;
            $data['date_in_at'] = $fromDateParse->toDateString();
            $data['time_in_at'] = $fromDateParse->toTimeString();
            $data['date_out_at'] = $toDateParse->toDateString();
            $data['time_out_at'] = $toDateParse->toTimeString();
            $data['schedule_date_in_at'] = $shiftSchedule->date;
            $data['schedule_time_in_at'] = Carbon::parse($shiftSchedule->time_in)->toTimeString();
            $data['schedule_date_out_at'] = $shiftSchedule->date;
            $data['schedule_time_out_at'] = Carbon::parse($shiftSchedule->time_out)->toTimeString();
            $data['holiday'] = $shiftSchedule->holiday;
            $data['night'] = $shiftSchedule->night;
            $data['national_holiday'] = $shiftSchedule->national_holiday;
            $data['function'] = '';
            $data['note'] = '';
            $data['type'] = 'SPL';
            $data['overtime_type'] = $data['type'];
            $data['overtime_hours'] = $data['duration'];
            $data['shift_schedule_id'] = $shiftSchedule->id;
            GenerateAbsen::create($data);
        }
        // send firebase notification
        $typeSend = 'Overtime';
        $registrationIds = [];
        $employee = $this->employeeService->show($overtime->employee_id);
        $getHakAkses = User::where('username',$employee->employment_number)->get()->first();
        $registrationIds[] = $getHakAkses->firebase_id;

        // notif ke HRDs
        $employeeHrd = User::where('hrd','1')->get();
        foreach ($employeeHrd as $key ) {
            # code...
            $firebaseIdx = $key;
        }

        // if($employee->supervisor != null){
        //     if($employee->supervisor->user != null){
        //         $registrationIds[] = $employee->supervisor->user->firebase_id;
        //     }
        // }
        if($employee->kabag_id != null ){
            if($employee->kabag->user != null){
                $registrationIds[] = $employee->kabag->user->firebase_id;
            }
        }
        if($employee->manager_id != null ){
            if($employee->manager->user != null){
                $registrationIds[] = $employee->manager->user->firebase_id;
            }
        }

        // Check if there are valid registration IDs before sending the notification
        if (!empty($registrationIds)) {
            $this->firebaseService->sendNotification($registrationIds, $typeSend, $employee->name);
        }

        return [
            'message' => 'Overtime created successfully',
            'success' => true,
            'code' => 201,
            'data' => [$overtime]
        ];
    }

    public function show($id)
    {
        $overtime = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            },
                            'overtimeStatus' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'overtimeHistory' => function ($query) {
                                $query->select(
                                    'id',
                                    'overtime_id',
                                    'user_id',
                                    'description',
                                    'ip_address',
                                    'user_agent',
                                    'comment',
                                    'libur',
                                );
                            },
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $overtime ? $overtime : $overtime = null;
    }

    public function update($id, $data)
    {
        $overtime = $this->model->with('overtimeStatus')->find($id);
        // save to table overtime history
        $historyData = [
            'overtime_id' => $overtime->id,
            'user_id' => auth()->id(),
            'description' => 'OVERTIME STATUS '. $overtime->overtimeStatus->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'comment' => $data['note'],
            'libur' => $data['libur'],
        ];
        $this->overtimeHistoryService->store($historyData);
        // send firebase notification
        $typeSend = 'Overtime';
        $employee = $this->employeeService->show($overtime->employee_id);
        $registrationIds = [];
        $getHakAkses = User::where('username',$employee->employment_number)->get()->first();
        $registrationIds[] = $getHakAkses->firebase_id;

                    // notif ke HRDs
                    $employeeHrd = User::where('hrd','1')->get();
                    foreach ($employeeHrd as $key ) {
                        # code...
                        $firebaseIdx = $key;
                    }

        if($employee->supervisor != null){
            if($employee->supervisor->user != null){
                $registrationIds[] = $employee->supervisor->user->firebase_id;
            }
        }
        if($employee->kabag_id != null ){
            if($employee->kabag->user != null){
                $registrationIds[] = $employee->kabag->user->firebase_id;
            }
        }
        if($employee->manager_id != null ){
            if($employee->manager->user != null){
                $registrationIds[] = $employee->manager->user->firebase_id;
            }
        }
        // Check if there are valid registration IDs before sending the notification
        if (!empty($registrationIds)) {
            $this->firebaseService->sendNotification($registrationIds, $typeSend, $employee->name);
        }
        if ($overtime) {
            $overtime->update($data);
            return $overtime;
        }
        return null;
    }

    public function destroy($id)
    {
        $overtime = $this->model->find($id);
        if ($overtime) {
            $this->overtimeHistoryService->deleteByOvertimeId($id);
            $overtime->delete();
            return $overtime;
        }
        return null;
    }

    public function overtimeEmployee($perPage, $overtimeStatus = null, $startDate = null, $endDate = null)
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
                        'overtimeStatus' => function ($query) {
                            $query->select('id', 'name');
                        },
                    ])
                    ->select($this->field);
        $query->where('employee_id', $user->employee->id);
        if ($overtimeStatus) {
            $query->where('overtime_status_id', $overtimeStatus);
        }
        if ($startDate) {
            $query->whereDate('from_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('from_date', '<=', $endDate);
        }
        return $query->paginate($perPage);
    }

    public function overtimeEmployeeMobile($employeeId)
    {
        $employee = Employee::where('employment_number', $employeeId)->first();
        if (!$employee) {
            return [];
        }
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $overtime = DB::table('overtimes')
                        ->select([
                            DB::raw("COALESCE(overtimes.id, '') as id"),
                            DB::raw("COALESCE(overtimes.employee_id, '') as employee_id"),
                            DB::raw("COALESCE(overtimes.task, '') as task"),
                            DB::raw("COALESCE(overtimes.note, '') as note"),
                            DB::raw("COALESCE(overtimes.overtime_status_id::text, '') as overtime_status_id"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.from_date, 'YYYY-MM-DD HH24:MI:SS'), '') as from_date"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.to_date, 'YYYY-MM-DD HH24:MI:SS'), '') as to_date"),
                            DB::raw("COALESCE(overtimes.amount::text, '') as amount"),
                            DB::raw("COALESCE(overtimes.type, '') as type"),
                            DB::raw("COALESCE(overtimes.libur::text, '') as libur"),
                            DB::raw("COALESCE(overtimes.duration::text, '') as duration"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.created_at, 'YYYY-MM-DD'), '') as tglinput"),
                            DB::raw("COALESCE(employees.name, '') as employee_name"),
                            DB::raw("COALESCE(overtime_statuses.name, '') as overtime_status_name"),
                        ])
                        ->leftJoin('employees', 'overtimes.employee_id', '=', 'employees.id')
                        ->leftJoin('overtime_statuses', 'overtimes.overtime_status_id', '=', 'overtime_statuses.id')
                        ->where('overtimes.employee_id', $employee->id)
                        ->whereBetween('overtimes.from_date', [$startOfMonth, $endOfMonth])
                        ->orderBy('overtimes.from_date', 'DESC')
                        ->get();
        return $overtime ? $overtime : $overtime = null;
    }

    public function overtimeHrdMobile()
    {
        $overtime = DB::table('overtimes')
                        ->select([
                            DB::raw("COALESCE(overtimes.id, '') as id"),
                            DB::raw("COALESCE(overtimes.employee_id, '') as employee_id"),
                            DB::raw("COALESCE(overtimes.task, '') as task"),
                            DB::raw("COALESCE(overtimes.note, '') as note"),
                            DB::raw("COALESCE(overtimes.overtime_status_id::text, '') as overtime_status_id"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.from_date, 'YYYY-MM-DD HH24:MI:SS'), '') as from_date"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.to_date, 'YYYY-MM-DD HH24:MI:SS'), '') as to_date"),
                            DB::raw("COALESCE(overtimes.amount::text, '') as amount"),
                            DB::raw("COALESCE(overtimes.type, '') as type"),
                            DB::raw("COALESCE(overtimes.libur::text, '') as libur"),
                            DB::raw("COALESCE(overtimes.duration::text, '') as duration"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.created_at, 'YYYY-MM-DD'), '') as tglinput"),
                            DB::raw("COALESCE(employees.name, '') as employee_name"),
                            DB::raw("COALESCE(overtime_statuses.name, '') as overtime_status_name"),
                        ])
                        ->leftJoin('employees', 'overtimes.employee_id', '=', 'employees.id')
                        ->leftJoin('overtime_statuses', 'overtimes.overtime_status_id', '=', 'overtime_statuses.id')
                        ->whereIn('overtimes.overtime_status_id', [1, 2, 3, 4])
                        ->orderBy('overtimes.from_date', 'DESC')
                        ->get();
        return $overtime ? $overtime : $overtime = null;
    }

    public function overtimeSupervisorOrManager($perPage, $search, $overtimeStatus = null, $startDate = null, $endDate = null)
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
                        'overtimeStatus' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'overtimeHistory' => function ($query) {
                            $query->select(
                                'id',
                                'overtime_id',
                                'user_id',
                                'description',
                                'ip_address',
                                'user_agent',
                                'comment',
                                'libur',
                            );
                        },
                    ])
                    ->select($this->field);

        // Filter overtime data for supervised or managed employees
        $query->whereIn('employee_id', $subordinateIds);

        if ($overtimeStatus) {
            $query->whereIn('overtime_status_id', $overtimeStatus);
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
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
                                                ->orWhere('employment_number', 'like', '%' . $search . '%');
                            });
            });
        }
        return $query->paginate($perPage);
    }

    public function overtimeSupervisorOrManagerMobile($employeeId)
    {
        $subordinateIds = Employee::where('supervisor_id', $employeeId)
                                    ->orWhere('manager_id', $employeeId)
                                    ->orWhere('kabag_id', $employeeId)
                                    ->pluck('id');
        return DB::table('overtimes')
                    ->leftJoin('employees', 'overtimes.employee_id', '=', 'employees.id')
                    ->leftJoin('overtime_statuses', 'overtimes.overtime_status_id', '=', 'overtime_statuses.id')
                    ->select([
                        'overtimes.id',
                        'overtimes.employee_id',
                        'overtimes.task',
                        'overtimes.note',
                        'overtimes.overtime_status_id',
                        'overtimes.from_date',
                        'overtimes.amount',
                        'overtimes.type',
                        'overtimes.to_date',
                        'overtimes.duration',
                        'overtimes.libur',
                        'employees.name as employee_name',
                        'overtime_statuses.name as overtime_status_name',
                    ])
                    ->whereIn('overtimes.employee_id', $subordinateIds)
                    ->get();
    }

    public function overtimeStatus($perPage, $search = null, $overtimeStatus = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name', 'employment_number');
                        },
                        'overtimeStatus' => function ($query) {
                            $query->select('id', 'name');
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

        if ($overtimeStatus) {
            $query->whereIn('overtime_status_id', $overtimeStatus);
        }
        return $query->paginate($perPage);
    }

    public function updateStatus($id, $data)
    {
        $overtime = $this->model->find($id);
        if ($overtime) {
            $overtime->update(['overtime_status_id' => $data['overtime_status_id']]);
            // save to table overtime history
            $historyData = [
                'overtime_id' => $overtime->id,
                'user_id' => auth()->id(),
                'description' => 'OVERTIME STATUS '. $overtime->overtimeStatus->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'comment' => $overtime->note,
            ];
            $this->overtimeHistoryService->store($historyData);
            // delete overtime if employee non shift if batal
            // if ($overtime->leave_type_id == 1 && $data['overtime_status_id'] == 10) {
            //     $employee = $this->employeeService->show($overtime->employee_id);
            //     // update Shift Schedule if shift, delete if non shift
            //     ShiftSchedule::where('leave_id', $overtime->id)->delete();
            //     OvertimeHistory::where('leave_id', $overtime->id)->delete();
            //     $overtime->delete();
            // }
            $typeSend = 'Overtime Update';
            $employee = $this->employeeService->show($overtime->employee_id);
            $registrationIds = [];
            if($employee->user != null){
                $registrationIds[] = $employee->user->firebase_id;
            }
            // notif ke HRDs
            $employeeHrd = User::where('hrd','1')->get();
            foreach ($employeeHrd as $key ) {
                # code...
                $firebaseIdx = $key;
            }
            // Check if there are valid registration IDs before sending the notification
            if (!empty($registrationIds)) {
                $this->firebaseService->sendNotification($registrationIds, $typeSend, $employee->name);
            }
            return $overtime;
        }
        return null;
    }

    public function updateStatusMobile($overtimeId, $overtimeStatusId)
    {
        $overtime = $this->model->find($overtimeId);
        if ($overtime) {
            $overtime->update(['overtime_status_id' => $overtimeStatusId]);
            // save to table overtime history
            $historyData = [
                'overtime_id' => $overtime->id,
                'user_id' => auth()->id(),
                'description' => 'OVERTIME STATUS '. $overtime->overtimeStatus->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'comment' => $overtime->note,
                'libur' => $overtime->libur,
            ];
            $this->overtimeHistoryService->store($historyData);
            $typeSend = 'Overtime Update';
            $employee = $this->employeeService->show($overtime->employee_id);
            $registrationIds = [];
            if($employee->user != null){
                $registrationIds[] = $employee->user->firebase_id;
            }

            // notif ke HRDs
            $employeeHrd = User::where('hrd','1')->get();
            foreach ($employeeHrd as $key ) {
                # code...
                $firebaseIdx = $key;
            }
            // Check if there are valid registration IDs before sending the notification
            if (!empty($registrationIds)) {
                $this->firebaseService->sendNotification($registrationIds, $typeSend, $employee->name);
            }
            return $overtime;
        }
        return null;
    }

    public function overtimeEmployeeToday($employeeId)
    {
        $employee = Employee::where('employment_number', $employeeId)->first();
        if (!$employee) {
            return [];
        }
        $overtime = DB::table('overtimes')
                        ->select([
                            DB::raw("COALESCE(overtimes.id, '') as id"),
                            DB::raw("COALESCE(overtimes.employee_id, '') as employee_id"),
                            DB::raw("COALESCE(overtimes.task, '') as task"),
                            DB::raw("COALESCE(overtimes.note, '') as note"),
                            DB::raw("COALESCE(overtimes.overtime_status_id::text, '') as overtime_status_id"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.from_date, 'YYYY-MM-DD HH24:MI:SS'), '') as from_date"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.to_date, 'YYYY-MM-DD HH24:MI:SS'), '') as to_date"),
                            DB::raw("COALESCE(overtimes.amount::text, '') as amount"),
                            DB::raw("COALESCE(overtimes.type, '') as type"),
                            DB::raw("COALESCE(overtimes.libur::text, '') as libur"),
                            DB::raw("COALESCE(overtimes.duration::text, '') as duration"),
                            DB::raw("COALESCE(employees.name, '') as employee_name"),
                            DB::raw("COALESCE(overtime_statuses.name, '') as overtime_status_name"),
                        ])
                        ->leftJoin('employees', 'overtimes.employee_id', '=', 'employees.id')
                        ->leftJoin('overtime_statuses', 'overtimes.overtime_status_id', '=', 'overtime_statuses.id')
                        ->where('overtimes.employee_id', $employee->id)
                        ->where('from_date', '>=', Carbon::today()->startOfDay())
                        ->where('from_date', '<', Carbon::tomorrow()->startOfDay())
                        ->first($this->field);
        return $overtime ? $overtime : $overtime = null;
    }
}
