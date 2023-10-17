<?php

namespace App\Repositories\Overtime;

use App\Models\Overtime;
use App\Repositories\Overtime\OvertimeRepositoryInterface;


class OvertimeRepository implements OvertimeRepositoryInterface
{
    private $model;
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
    ];

    public function __construct(Overtime $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name');
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
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
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
        $overtime = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'overtimeStatus' => function ($query) {
                                $query->select('id', 'name');
                            },
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $overtime ? $overtime : $overtime = null;
    }

    public function update($id, $data)
    {
        $overtime = $this->model->find($id);
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
                            $query->select('id', 'name');
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

    public function overtimeStatus($perPage, $search = null, $overtimeStatus = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name');
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
                                $employeeQuery->where('name', 'like', '%' . $search . '%');
                            });
            });
        }

        if ($overtimeStatus) {
            $query->where('overtime_status_id', $overtimeStatus);
        }
        return $query->paginate($perPage);
    }

    public function updateStatus($id, $data)
    {
        $overtime = $this->model->find($id);
        if ($overtime) {
            $overtime->update(['overtime_status_id' => $data['overtime_status_id']]);
            return $overtime;
        }
        return null;
    }
}
