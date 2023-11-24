<?php

namespace App\Repositories\GenerateAbsen;

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
        'input_manual_at', 'lock', 'gp_in', 'gp_out',
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
}
