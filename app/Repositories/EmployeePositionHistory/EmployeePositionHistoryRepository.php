<?php

namespace App\Repositories\EmployeePositionHistory;

use App\Models\EmployeePositionHistory;
use App\Repositories\EmployeePositionHistory\EmployeePositionHistoryRepositoryInterface;


class EmployeePositionHistoryRepository implements EmployeePositionHistoryRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'employee_id',
        'position_id',
        'unit_id',
        'department_id',
        'started_at',
        'ended_at',
    ];

    public function __construct(EmployeePositionHistory $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name', 'employment_number');
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
        return $this->model->create($data);
    }

    public function show($id)
    {
        $employeepositionhistory = $this->model
                                    ->with([
                                        'employee' => function ($query) {
                                            $query->select('id', 'name', 'employment_number');
                                        },
                                        'position' => function ($query) {
                                            $query->select('id', 'name');
                                        },
                                        'unit' => function ($query) {
                                            $query->select('id', 'name');
                                        },
                                        'department' => function ($query) {
                                            $query->select('id', 'name');
                                        },
                                    ])
                                    ->where('id', $id)
                                    ->first($this->field);
        return $employeepositionhistory ? $employeepositionhistory : $employeepositionhistory = null;
    }

    public function update($id, $data)
    {
        $employeepositionhistory = $this->model->find($id);
        if ($employeepositionhistory) {
            $employeepositionhistory->update($data);
            return $employeepositionhistory;
        }
        return null;
    }

    public function destroy($id)
    {
        $employeepositionhistory = $this->model->find($id);
        if ($employeepositionhistory) {
            $employeepositionhistory->delete();
            return $employeepositionhistory;
        }
        return null;
    }
}
