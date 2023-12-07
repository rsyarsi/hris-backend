<?php

namespace App\Repositories\TimesheetOvertime;

use App\Models\TimesheetOvertime;
use App\Repositories\TimesheetOvertime\TimesheetOvertimeRepositoryInterface;


class TimesheetOvertimeRepository implements TimesheetOvertimeRepositoryInterface
{
    private $model;
    private $field = 
    [
        'employee_id',
        'employee_name',
        'unitname',
        'positionname',
        'departmenname',
        'overtime_type',
        'realisasihours',
        'schedule_date_in_at',
        'schedule_time_in_at',
        'schedule_date_out_at',
        'schedule_time_out_at',
        'date_in_at',
        'time_in_at',
        'date_out_at',
        'time_out_at',
        'jamlemburawal',
        'jamlemburconvert',
        'jamlembur',
        'tuunjangan',
        'uanglembur',
        'period',
    ];

    public function __construct(TimesheetOvertime $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with(['employee' => function ($query)
                        {
                            $query->select('id', 'name', 'employment_number');
                        },
                    ])
                    ->select($this->field)
                    ->where(function ($query) use ($search) {
                        $query->where('period', 'like', "%{$search}%")
                            ->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->where('name', 'like', "%{$search}%");
                            });
                    });
        return $query->orderBy('period', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $position = $this->model
                        ->with(['employee' => function ($query)
                            {
                                $query->select('id', 'name', 'employment_number');
                            },
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $position ? $position : $position = null;
    }

    public function update($id, $data)
    {
        $position = $this->model->find($id);
        if ($position) {
            $position->update($data);
            return $position;
        }
        return null;
    }

    public function destroy($id)
    {
        $position = $this->model->find($id);
        if ($position) {
            $position->delete();
            return $position;
        }
        return null;
    }

    public function timesheetOvertimeEmployee($perPage, $search = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }
        $query = $this->model
                        ->select($this->field)
                        ->where(function ($query) use ($search) {
                            $query->where('period', 'like', "%{$search}%");
                        });
        $query->where('employee_id', $user->employee->id);
        return $query->orderBy('period', 'DESC')->paginate($perPage);
    }
}
