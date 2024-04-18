<?php

namespace App\Repositories\LogGenerateAbsen;

use App\Models\LogGenerateAbsen;
use App\Repositories\LogGenerateAbsen\LogGenerateAbsenRepositoryInterface;

class LogGenerateAbsenRepository implements LogGenerateAbsenRepositoryInterface
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

    public function __construct(LogGenerateAbsen $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null, $period_1 = null, $period_2 = null, $unit = null)
    {
        $query = $this->model
            ->with([
                'employee:id,name,email,employment_number',
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
                'leave:id,from_date,to_date,duration,note',
                'leaveType:id,name,is_salary_deduction,active',
                'user:id,name,email',
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

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $logGenerateAbsen = $this->model
            ->with([
                'employee:id,name,email,employment_number',
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
                'leave:id,from_date,to_date,duration,note',
                'leaveType:id,name,is_salary_deduction,active',
                'user:id,name,email',
            ])
            ->where('id', $id)
            ->first($this->field);
        return $logGenerateAbsen ? $logGenerateAbsen : $logGenerateAbsen = null;
    }

    public function findDate($employeeId, $date)
    {
        return $this->model
            ->where('employee_id', $employeeId)
            ->where('date', $date)
            ->first();
    }
}
