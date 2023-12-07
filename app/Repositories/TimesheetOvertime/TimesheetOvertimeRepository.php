<?php

namespace App\Repositories\TimesheetOvertime;

use App\Models\TimesheetOvertime;
use App\Repositories\TimesheetOvertime\TimesheetOvertimeRepositoryInterface;


class TimesheetOvertimeRepository implements TimesheetOvertimeRepositoryInterface
{
    private $model;
    private $field =
    [
        'id',
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
        return $query->orderBy('schedule_date_in_at', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $timesheetOvertime = $this->model
                                    ->with(['employee' => function ($query)
                                        {
                                            $query->select('id', 'name', 'employment_number');
                                        },
                                    ])
                                    ->where('id', $id)
                                    ->first($this->field);
        return $timesheetOvertime ? $timesheetOvertime : $timesheetOvertime = null;
    }

    public function update($id, $data)
    {
        $timesheetOvertime = $this->model->find($id);
        if ($timesheetOvertime) {
            $timesheetOvertime->update($data);
            return $timesheetOvertime;
        }
        return null;
    }

    public function destroy($id)
    {
        $timesheetOvertime = $this->model->find($id);
        if ($timesheetOvertime) {
            $timesheetOvertime->delete();
            return $timesheetOvertime;
        }
        return null;
    }

    public function timesheetOvertimeEmployee($perPage, $search = null, $employeeId)
    {
        $query = $this->model->select($this->field);
        if ($search !== null) {
            $query->whereRaw('period LIKE ?', "%". $search ."%");
        }
        $query->where('employee_id', $employeeId);
        return $query->orderBy('schedule_date_in_at', 'DESC')->paginate($perPage);
    }
}
