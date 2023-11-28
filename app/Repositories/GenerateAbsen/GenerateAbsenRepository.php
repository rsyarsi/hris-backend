<?php

namespace App\Repositories\GenerateAbsen;

use Carbon\Carbon;
use App\Models\GenerateAbsen;
use App\Repositories\GenerateAbsen\GenerateAbsenRepositoryInterface;


class GenerateAbsenRepository implements GenerateAbsenRepositoryInterface
{
    private $model;
    private $field =
    [
        'id', 'period', 'date', 'day', 'employee_id', 'shift_id', 'date_in_at', 'time_in_at',
        'date_out_at', 'time_out_at', 'schedule_date_in_at', 'schedule_time_in_at',
        'schedule_date_out_at', 'schedule_time_out_at', 'telat', 'pa', 'holiday',
        'night', 'national_holiday', 'note', 'leave_id', 'leave_type_id', 'leave_time_at',
        'leave_out_at', 'schedule_leave_time_at', 'schedule_leave_out_at', 'overtime_id',
        'overtime_at', 'overtime_time_at', 'overtime_out_at', 'schedule_overtime_time_at',
        'schedule_overtime_out_at', 'ot1', 'ot2', 'ot3', 'ot4', 'manual', 'user_manual_id',
        'input_manual_at', 'lock', 'gp_in', 'gp_out', 'type'
    ];

    public function __construct(GenerateAbsen $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null, $period_1 = null, $period_2 = null, $unit = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name', 'unit_id')->with('unit:id,name');
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
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $generateAbsen = $this->model->where('id', $id)->first($this->field);
        return $generateAbsen ? $generateAbsen : $generateAbsen = null;
    }

    public function update($id, $data)
    {
        $generateAbsen = $this->model->find($id);
        if ($generateAbsen) {
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

    public function absenFromMobile(array $data)
    {
        $employeeId = $data['employee_id'];
        $date = $data['date'];
        $type = $data['type']; // ABSEN / SPL(SURAT PERINTAH LEMBUR)
        $function = $data['function']; // IN / OUT

        // ABSEN
        if ($type == 'ABSEN') {
            $existingRecordAbsen = $this->model
                                        ->where('employee_id', $employeeId)
                                        ->where('date', $date)
                                        ->where('type', 'ABSEN')
                                        ->first();

            if ($type == 'ABSEN' && $function == 'IN') {
                if ($existingRecordAbsen) {
                    return [
                        'message' => 'Anda Sudah Absen Masuk!',
                        'data' => []
                    ];
                } else { // Create a new record
                    $data['time_out_at'] = null;
                    return [
                        'message' => 'Absen Masuk Berhasil!',
                        'data' => [$this->model->create($data)]
                    ];
                }
            }

            if ($type == 'ABSEN' && $function == 'OUT') {
                if ($existingRecordAbsen) {
                    if ($existingRecordAbsen->time_out_at !== null) {
                        return [
                            'message' => 'Anda Sudah Absen Keluar!',
                            'data' => []
                        ];
                    } else { // Check if a record exists for the employee and date
                        $timeOutAt = $data['time_out_at'];
                        $scheduleTimeOutAt = $existingRecordAbsen->schedule_time_out_at;
                        $pa = null; //pa = pulang awal
                        if (Carbon::parse($scheduleTimeOutAt)->greaterThan($timeOutAt)) {
                            $pa = Carbon::parse($scheduleTimeOutAt)->diffInMinutes($timeOutAt);
                        }
                        $existingRecordAbsen->update([
                            'time_out_at' => $data['time_out_at'],
                            'pa' => $pa,
                        ]);
                        return [
                            'message' => 'Absen Keluar Berhasil!',
                            'data' => [$existingRecordAbsen]
                        ];
                    }
                } else {
                    // Handle the case where $existingRecordAbsen is null (data doesn't exist)
                    // You might want to return an appropriate response or handle it accordingly
                    return [
                        'message' => 'Anda Belum Absen/Data not found',
                        'data' => []
                    ];
                }
            }
        } else {
            // OVERTIME
            $existingRecordOvertime = $this->model
                            ->where('employee_id', $employeeId)
                            ->where('date', $date)
                            ->where('type', 'SPL')
                            ->first();
            // return $existingRecordOvertime;
            if ($existingRecordOvertime && $type == 'SPL' && $function == 'IN') {
                return [
                    'message' => 'Anda Sudah Absen Overtime Masuk!',
                    'data' => []
                ];
            }
            elseif (!$existingRecordOvertime && $type == 'SPL' && $function == 'IN') { // Create a new record
                $data['time_out_at'] = null;
                $data['telat'] = null;
                return [
                    'message' => 'Absen Keluar Berhasil!',
                    'data' => [$this->model->create($data)]
                ];
            }

            if ($existingRecordOvertime->time_out_at !== null && $type == 'SPL' && $function == 'OUT') {
                return [
                    'message' => 'Anda Sudah Absen Overtime Pulang!',
                    'data' => []
                ];
            }
            elseif ($existingRecordOvertime->time_out_at == null && $type == 'SPL' && $function == 'OUT') { // Check if a record exists for the employee and date
                $timeOutAt = $data['time_out_at'];
                $scheduleTimeOutAt = $existingRecordOvertime->schedule_time_out_at;
                $pa = null; //pa = pulang awal
                if (Carbon::parse($scheduleTimeOutAt)->greaterThan($timeOutAt)) {
                    $pa = Carbon::parse($scheduleTimeOutAt)->diffInMinutes($timeOutAt);
                }
                $existingRecordOvertime->update([
                    'time_out_at' => $data['time_out_at'],
                    'pa' => null,
                ]);
                return [
                    'message' => 'Absen Keluar Berhasil!',
                    'data' => [$existingRecordOvertime]
                ];
            }
        }
    }
}
