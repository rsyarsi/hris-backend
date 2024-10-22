<?php

namespace App\Repositories\GenerateAbsen;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\GenerateAbsen;
use Illuminate\Support\Facades\DB;
use App\Repositories\GenerateAbsen\GenerateAbsenRepositoryInterface;
use App\Services\LogGenerateAbsen\LogGenerateAbsenServiceInterface;

class GenerateAbsenRepository implements GenerateAbsenRepositoryInterface
{
    private $model;
    private $logGenerateAbsen;
    private $field =
    [
        'id', 'period', 'date', 'day', 'employee_id', 'shift_id', 'date_in_at', 'time_in_at',
        'date_out_at', 'time_out_at', 'schedule_date_in_at', 'schedule_time_in_at',
        'schedule_date_out_at', 'schedule_time_out_at', 'telat', 'pa', 'holiday',
        'night', 'national_holiday', 'note', 'leave_id', 'leave_type_id', 'leave_time_at',
        'leave_out_at', 'schedule_leave_time_at', 'schedule_leave_out_at', 'overtime_id',
        'overtime_at', 'overtime_time_at', 'overtime_out_at', 'schedule_overtime_time_at',
        'schedule_overtime_out_at', 'ot1', 'ot2', 'ot3', 'ot4', 'manual', 'user_manual_id',
        'input_manual_at', 'lock', 'gp_in', 'gp_out', 'type', 'shift_schedule_id'
    ];
    private $fieldShift = [
        'id',
        'code',
        'name',
        'in_time',
        'out_time',
        'finger_in_less',
        'finger_in_more',
        'finger_out_less',
        'finger_out_more',
        'night_shift',
    ];
    private $fieldShiftSchedule = [
        'id',
        'employee_id',
        'shift_id',
        'date',
        'time_in',
        'time_out',
        'late_note',
        'leave_id',
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
    ];

    public function __construct(GenerateAbsen $model, LogGenerateAbsenServiceInterface $logGenerateAbsen)
    {
        $this->model = $model;
        $this->logGenerateAbsen = $logGenerateAbsen;
    }

    public function index($perPage, $search = null, $period_1 = null, $period_2 = null, $unit = null)
    {
        $query = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'unit_id', 'employment_number')->with('unit:id,name');
                },
                'shift' => function ($query) {
                    $query->select(
                        $this->fieldShift
                    );
                },
                'shiftSchedule' => function ($query) {
                    $query->select(
                        $this->fieldShiftSchedule
                    );
                },
                'leave' => function ($query) {
                    $query->select('id', 'from_date', 'to_date', 'duration', 'note');
                },
                'leaveType' => function ($query) {
                    $query->select('id', 'name', 'is_salary_deduction', 'active');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
            ])
            ->select($this->field);
        // Additional conditions
        if ($period_1 && $period_2) {
            $query->whereBetween('date', [$period_1, $period_2]);
        } elseif ($period_1) {
            $query->where('date', $period_1);
        } elseif ($period_2) {
            $query->where('date', $period_2);
        }
        if ($unit) {
            $query->whereHas('employee', function ($employeeQuery) use ($unit) {
                $employeeQuery->where('unit_id', $unit);
            });
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
        return $query->paginate($perPage);
    }

    public function generateAbsenEmployee($employeeId)
    {
        $query = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'unit_id', 'employment_number')->with('unit:id,name');
                },
                'shift' => function ($query) {
                    $query->select(
                        'id',
                        'code',
                        'name',
                        'in_time',
                        'out_time',
                        'finger_in_less',
                        'finger_in_more',
                        'finger_out_less',
                        'finger_out_more',
                        'night_shift',
                    );
                },
                'leave' => function ($query) {
                    $query->select('id', 'from_date', 'to_date', 'duration', 'note');
                },
                'leaveType' => function ($query) {
                    $query->select('id', 'name', 'is_salary_deduction', 'active');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
            ])
            ->select($this->field)
            ->where('employee_id', $employeeId);
        return $query->orderBy('date', 'DESC')->paginate();
    }

    public function monitoringAbsen($perPage, $search = null, $period_1 = null, $period_2 = null, $unit = null)
    {
        $query = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'employment_number', 'unit_id')->with('unit:id,name');
                },
                'shift' => function ($query) {
                    $query->select(
                        'id',
                        'code',
                        'name',
                        'in_time',
                        'out_time',
                        'finger_in_less',
                        'finger_in_more',
                        'finger_out_less',
                        'finger_out_more',
                        'night_shift',
                    );
                },
                'leave' => function ($query) {
                    $query->select('id', 'from_date', 'to_date', 'duration', 'note');
                },
                'leaveType' => function ($query) {
                    $query->select('id', 'name', 'is_salary_deduction', 'active');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
            ])
            ->select($this->field);

        // Additional conditions
        $query->where(function ($subquery) {
            $subquery->whereNull('leave_id')
                ->orWhere('leave_id', null)
                ->orWhere('leave_id', '');
        })
            ->where(function ($subquery) {
                $subquery->whereNull('holiday')
                    ->orWhere('holiday', 0);
            })
            ->where(function ($subquery) {
                $subquery->where(function ($timeQuery) {
                    $timeQuery->whereNull('time_in_at')
                        ->whereNull('time_out_at');
                })
                    ->orWhere(function ($timeQuery) {
                        $timeQuery->whereNotNull('time_in_at')
                            ->whereNull('time_out_at');
                    });
            });

        // Period conditions
        if ($period_1 && $period_2) {
            $query->whereBetween('date', [$period_1, $period_2]);
        } elseif ($period_1) {
            $query->where('date', $period_1);
        } elseif ($period_2) {
            $query->where('date', $period_2);
        }

        // Unit condition
        if ($unit) {
            $query->whereHas('employee', function ($employeeQuery) use ($unit) {
                $employeeQuery->where('unit_id', $unit);
            });
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

        return $query->orderBy('date', 'DESC')->paginate($perPage);
    }

    public function generateAbsenSubordinate($perPage, $search = null, $period_1 = null, $period_2 = null, $unit = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }
        $queryEmployee = Employee::where(function ($q) use ($user) {
            $q->where('supervisor_id', $user->employee->id)
                ->orWhere('manager_id', $user->employee->id)
                ->orWhere('kabag_id', $user->employee->id);
        })
            ->get();
        $employeeIds = []; // Collect employee IDs in an array
        foreach ($queryEmployee as $item) {
            $employeeIds[] = $item->id;
        }
        $query = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'unit_id', 'employment_number')->with('unit:id,name');
                },
                'shift' => function ($query) {
                    $query->select(
                        'id',
                        'code',
                        'name',
                        'in_time',
                        'out_time',
                        'finger_in_less',
                        'finger_in_more',
                        'finger_out_less',
                        'finger_out_more',
                        'night_shift',
                    );
                },
                'leave' => function ($query) {
                    $query->select('id', 'from_date', 'to_date', 'duration', 'note');
                },
                'leaveType' => function ($query) {
                    $query->select('id', 'name', 'is_salary_deduction', 'active');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
            ])
            ->whereIn('employee_id', $employeeIds)
            ->select($this->field);

        // Period conditions
        if ($period_1 && $period_2) {
            $query->whereBetween('date', [$period_1, $period_2]);
        } elseif ($period_1) {
            $query->where('date', $period_1);
        } elseif ($period_2) {
            $query->where('date', $period_2);
        }

        // Unit condition
        if ($unit) {
            $query->whereHas('employee', function ($employeeQuery) use ($unit) {
                $employeeQuery->where('unit_id', $unit);
            });
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

        return $query->orderBy('date', 'DESC')->paginate($perPage);
    }

    public function generateAbsenSubordinateMobile($employeeId, $search = null, $period_1 = null, $period_2 = null, $unit = null)
    {
        $employee = Employee::where('employment_number', $employeeId)->first();
        if (!$employee) {
            return [];
        }
        $queryEmployee = Employee::where(function ($q) use ($employee) {
            $q->where('supervisor_id', $employee->id)
                ->orWhere('manager_id', $employee->id)
                ->orWhere('kabag_id', $employee->id);
        })
            ->get();
        $employeeIds = []; // Collect employee IDs in an array
        foreach ($queryEmployee as $item) {
            $employeeIds[] = $item->id;
        }
        $query = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'unit_id', 'employment_number')->with('unit:id,name');
                },
                'shift' => function ($query) {
                    $query->select(
                        'id',
                        'code',
                        'name',
                        'in_time',
                        'out_time',
                        'finger_in_less',
                        'finger_in_more',
                        'finger_out_less',
                        'finger_out_more',
                        'night_shift',
                    );
                },
                'leave' => function ($query) {
                    $query->select('id', 'from_date', 'to_date', 'duration', 'note');
                },
                'leaveType' => function ($query) {
                    $query->select('id', 'name', 'is_salary_deduction', 'active');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
            ])
            ->whereIn('employee_id', $employeeIds)
            ->select($this->field);

        // Period conditions
        if ($period_1 && $period_2) {
            $query->whereBetween('date', [$period_1, $period_2]);
        } elseif ($period_1) {
            $query->where('date', $period_1);
        } elseif ($period_2) {
            $query->where('date', $period_2);
        }

        // Unit condition
        if ($unit) {
            $query->whereHas('employee', function ($employeeQuery) use ($unit) {
                $employeeQuery->where('unit_id', $unit);
            });
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

        return $query->get();
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $generateAbsen = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'email', 'employment_number');
                },
                'shift' => function ($query) {
                    $query->select(
                        $this->fieldShift
                    );
                },
                'shiftSchedule' => function ($query) {
                    $query->select(
                        $this->fieldShiftSchedule
                    );
                },
                'leave' => function ($query) {
                    $query->select('id', 'from_date', 'to_date', 'duration', 'note');
                },
                'leaveType' => function ($query) {
                    $query->select('id', 'name', 'is_salary_deduction', 'active');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
            ])
            ->where('id', $id)
            ->first($this->field);
        return $generateAbsen ? $generateAbsen : $generateAbsen = null;
    }

    public function update($id, $data)
    {
        $generateAbsen = $this->model->find($id);
        if ($generateAbsen) {
            $leaveId = $data['leave_id'] ?? null;
            $timeOutAt = $data['time_out_at'];
            if ($timeOutAt !== null) {
                $scheduleTimeOutAt = $generateAbsen->schedule_time_out_at;
                $pa = null; // pa = pulang awal
                if (Carbon::parse($scheduleTimeOutAt)->greaterThan($timeOutAt)) {
                    $pa = Carbon::parse($scheduleTimeOutAt)->diffInMinutes($timeOutAt);
                }
                $data['pa'] = $leaveId == null ? $pa : null;
                $data['note'] = $pa == null && $data['telat'] == null ? null : 'WARNING';
            }
            $generateAbsen->update($data);
            return $generateAbsen;
        }
        return null;
    }

    public function destroy($id)
    {
        $generateAbsen = $this->model->find($id);
        if ($generateAbsen) {
            $generateAbsen->delete();
            return $generateAbsen;
        }
        return null;
    }

    public function findDate($employeeId, $date)
    {
        return $this->model
            ->where('employee_id', $employeeId)
            ->where('date', $date)
            ->first();
    }

    public function absenFromMobile(array $data)
    {
        DB::beginTransaction();
        try {
            $idSchedule = $data['Id_schedule'];
            $employeeId = $data['employee_id'];
            $date = $data['date'];
            $type = $data['type']; // ABSEN / SPL(SURAT PERINTAH LEMBUR)
            $function = $data['function']; // IN / OUT
            $overtimeId = $data['Overtime_id']; // OVERTIME ID YANG DIKIRIM DARI FE
            // $fromDateOvertime = Carbon::parse($data['from_date_overtime']); // FROM DATE OVERTIME DI TABLE OVERTIMES
            // $toDateOvertime = Carbon::parse($data['to_date_overtime']); // TO DATE OVERTIME DI TABLE OVERTIMES
            // $durationOvertime = $data['duration_overtime']; // DURATION OVERTIME DI TABLE OVERTIMES
            $date = $data['date']; // TIME IN DARI FE
            $timeIn = $data['time_in_at']; // TIME IN DARI FE
            // $timestampTimeIn = Carbon::parse($date.''.$timeIn);
            // response variable
            $finalData = "";
            $message = "";
            // ABSEN
            if ($type == 'ABSEN') {
                $existingRecordAbsen = $this->model
                    ->where('shift_schedule_id', $idSchedule)
                    ->where('employee_id', $employeeId)
                    // ->where('date', $date)
                    // ->orWhere('date_out_at', $date)
                    ->where('type', 'ABSEN')
                    ->first();
                if ($function == 'IN') {
                    if ($existingRecordAbsen && $existingRecordAbsen->note == "TIDAK ABSEN MASUK") { // CASE TIDAK ABSEN MASUK
                        $message = 'ANDA TIDAK BISA ABSEN MASUK!';
                        $finalData = $existingRecordAbsen;
                        $log = $this->logGenerateAbsen->store($data);
                        $this->logGenerateAbsen->update($log->id, [
                            'message' => $message,
                            'success' => false,
                            'code' => 200,
                            'data' => null,
                        ]);
                    } else if ($existingRecordAbsen && $existingRecordAbsen->time_in_at == null) { // update absen (NON SHIFT);
                        $existingRecordAbsen->update([
                            'date_in_at' => $data['date_in_at'],
                            'time_in_at' => $data['time_in_at'],
                            'date_out_at' => now()->format('Y-m-d'),
                            'time_out_at' => null,
                            'telat' => $data['telat'],
                            'note' => $data['telat'] !== null ? 'WARNING' : 'BELUM ABSEN PULANG',
                        ]);
                        $message = 'Absen Masuk Berhasil!';
                        $finalData = $existingRecordAbsen;
                        $log = $this->logGenerateAbsen->store($data);
                        $this->logGenerateAbsen->update($log->id, [
                            'message' => $message,
                            'success' => true,
                            'code' => 200,
                            'data' => null,
                        ]);
                    } else if ($existingRecordAbsen && $existingRecordAbsen->time_in_at !== null) {
                        $message = 'Anda Sudah Absen Masuk!';
                        $finalData = $existingRecordAbsen;
                        $log = $this->logGenerateAbsen->store($existingRecordAbsen->toArray());
                        $this->logGenerateAbsen->update($log->id, [
                            'message' => $message,
                            'success' => true,
                            'code' => 200,
                            'data' => null,
                        ]);
                    } else { // Create a new record
                        $data['time_out_at'] = null;
                        $data['note'] = "BELUM ABSEN PULANG";
                        $data['shift_schedule_id'] = $idSchedule;
                        $message = 'Absen Masuk Berhasil!';
                        $finalData = $this->model->create($data);
                        $log = $this->logGenerateAbsen->store($data);
                        $this->logGenerateAbsen->update($log->id, [
                            'message' => $message,
                            'success' => true,
                            'code' => 200,
                            'data' => null,
                        ]);
                    }
                }

                if ($function == 'OUT') {
                    $existingRecordAbsenOut = $this->model
                        ->where('shift_schedule_id', $idSchedule)
                        ->where('type', 'ABSEN')
                        ->first();
                    if ($existingRecordAbsenOut) {
                        if ($existingRecordAbsenOut->time_out_at !== null) {
                            $message = 'Anda Sudah Absen Keluar!';
                            $finalData = $existingRecordAbsenOut;
                            $log = $this->logGenerateAbsen->store($existingRecordAbsenOut->toArray());
                            $this->logGenerateAbsen->update($log->id, [
                                'message' => $message,
                                'success' => true,
                                'code' => 200,
                                'data' => null,
                            ]);
                        } else { // Check if a record exists for the employee and date
                            $timeOutAt = $data['time_out_at'];
                            $scheduleTimeOutAt = $existingRecordAbsenOut->schedule_time_out_at;
                            $pa = null; //pa = pulang awal
                            if (Carbon::parse($scheduleTimeOutAt)->greaterThan($timeOutAt)) {
                                $pa = Carbon::parse($scheduleTimeOutAt)->diffInMinutes($timeOutAt);
                            }
                            $existingRecordAbsenOut->update([
                                'time_out_at' => $data['time_out_at'],
                                'date_out_at' => now()->format('Y-m-d'),
                                'pa' => $pa,
                                'note' => $pa == null && $existingRecordAbsenOut->telat == null ? null : 'WARNING',
                            ]);
                            $message = 'Absen Keluar Berhasil!';
                            $finalData = $existingRecordAbsenOut;
                            $log = $this->logGenerateAbsen->store($existingRecordAbsenOut->toArray());
                            $this->logGenerateAbsen->update($log->id, [
                                'message' => $message,
                                'success' => true,
                                'code' => 200,
                                'data' => null,
                            ]);
                        }
                    } else {
                        $data['date_in_at'] = null;
                        $data['time_in_at'] = null;
                        $data['time_out_at'] = $data['time_out_at'];
                        $data['note'] = "TIDAK ABSEN MASUK";
                        $data['shift_schedule_id'] = $idSchedule;
                        $message = 'Absen Out Berhasil!';
                        $finalData = $this->model->create($data);
                        $log = $this->logGenerateAbsen->store($data);
                        $this->logGenerateAbsen->update($log->id, [
                            'message' => $message,
                            'success' => true,
                            'code' => 200,
                            'data' => null,
                        ]);
                    }
                }
            } else {
                // EXISTING RECORD
                $existingRecordOvertime = $this->model
                    ->where('employee_id', $employeeId)
                    ->where('type', 'SPL')
                    ->where('date', $date)
                    ->orWhere('overtime_out_at', $date)
                    ->first();

                if ($type == 'SPL' && $function == 'IN') {
                    if ($existingRecordOvertime == null) {
                        $data['overtime_id'] = $overtimeId;
                        $data['overtime_time_at'] = $timeIn;
                        $data['schedule_overtime_time_at'] = $data['from_date_overtime'] ?? null;
                        $data['schedule_overtime_out_at'] = $data['to_date_overtime'] ?? null;
                        $data['telat'] = 0;
                        $data['pa'] = 0;
                        $data['note'] = "BELUM ABSEN PULANG OVERTIME";
                        $data['time_out_at'] = null;
                        $data['overtime_out_at'] = null;
                        $data['shift_schedule_id'] = $idSchedule;
                        $data['overtime_hours'] = $data['duration_overtime'];
                        $data['overtime_type'] = $data['overtime_type'];
                        $message = 'Absen Masuk Overtime Berhasil!';
                        $finalData = $this->model->create($data);
                        $log = $this->logGenerateAbsen->store($data);
                        $this->logGenerateAbsen->update($log->id, [
                            'message' => $message,
                            'success' => true,
                            'code' => 200,
                            'data' => null,
                        ]);
                    } else {
                        $message = 'Anda Sudah Absen Masuk Overtime!';
                        $finalData = $existingRecordOvertime;
                        $log = $this->logGenerateAbsen->store($existingRecordOvertime->toArray());
                        $this->logGenerateAbsen->update($log->id, [
                            'message' => $message,
                            'success' => true,
                            'code' => 200,
                            'data' => null,
                        ]);
                    }
                }

                if ($type == 'SPL' && $function == 'OUT') {
                    $existingRecordSplOut = $this->model
                        ->where('shift_schedule_id', $idSchedule)
                        ->where('type', 'SPL')
                        ->first();
                    if ($existingRecordSplOut) {
                        if ($existingRecordSplOut->time_out_at !== null) {
                            $message = 'Anda Sudah Absen Overtime Keluar!';
                            $finalData = $existingRecordSplOut;
                            $log = $this->logGenerateAbsen->store($existingRecordSplOut->toArray());
                            $this->logGenerateAbsen->update($log->id, [
                                'message' => $message,
                                'success' => true,
                                'code' => 200,
                                'data' => null,
                            ]);
                        } else { // Check if a record exists for the employee and date
                            // CHECK TIME OUT
                            $finalOvertimeOutAt = $data['time_out_at'];
                            $scheduleOvertimeOutAt = Carbon::parse($existingRecordSplOut->schedule_overtime_out_at);
                            if ($data['time_out_at'] >= $scheduleOvertimeOutAt->format('H:i:s')) {
                                $finalOvertimeOutAt = $scheduleOvertimeOutAt->format('H:i:s');
                            }

                            $existingRecordSplOut->update([
                                'time_out_at' => $finalOvertimeOutAt,
                                'date_out_at' => now()->format('Y-m-d'),
                                'overtime_out_at' => $finalOvertimeOutAt,
                                'note' => null,
                            ]);
                            $message = 'Absen Keluar Overtime Berhasil!';
                            $finalData = $existingRecordSplOut;
                            $log = $this->logGenerateAbsen->store($existingRecordSplOut->toArray());
                            $this->logGenerateAbsen->update($log->id, [
                                'message' => $message,
                                'success' => true,
                                'code' => 200,
                                'data' => null,
                            ]);
                        }
                    } else {
                        $message = 'Anda Belum Absen Overtime/Data not found';
                        $finalData = [];
                        $log = $this->logGenerateAbsen->store($data);
                        $this->logGenerateAbsen->update($log->id, [
                            'message' => $message,
                            'success' => true,
                            'code' => 200,
                            'data' => null,
                        ]);
                    }
                }
            }

            DB::commit();
            return [
                'message' => $message,
                'data' => [$finalData]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error
            logger('Error during Absen From Mobile: ' . $e->getMessage());
            // Return error response
            $this->logGenerateAbsen->store([
                'message' => $e->getMessage(),
                'success' => false,
                'code' => 200,
                'data' => [],
            ]);
            return [
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }
}
