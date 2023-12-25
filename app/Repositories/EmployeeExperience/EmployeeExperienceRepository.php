<?php

namespace App\Repositories\EmployeeExperience;

use App\Models\EmployeeExperience;
use App\Repositories\EmployeeExperience\EmployeeExperienceRepositoryInterface;


class EmployeeExperienceRepository implements EmployeeExperienceRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'employee_id',
        'company_name',
        'company_field',
        'responsibility',
        'started_at',
        'ended_at',
        'start_position',
        'end_position',
        'stop_reason',
        'latest_salary'
    ];

    public function __construct(EmployeeExperience $model)
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
        $employeeexperience = $this->model
                                    ->with([
                                        'employee' => function ($query) {
                                            $query->select('id', 'name', 'employment_number');
                                        },
                                    ])
                                    ->where('id', $id)
                                    ->first($this->field);
        return $employeeexperience ? $employeeexperience : $employeeexperience = null;
    }

    public function update($id, $data)
    {
        $employeeexperience = $this->model->find($id);
        if ($employeeexperience) {
            $employeeexperience->update($data);
            return $employeeexperience;
        }
        return null;
    }

    public function destroy($id)
    {
        $employeeexperience = $this->model->find($id);
        if ($employeeexperience) {
            $employeeexperience->delete();
            return $employeeexperience;
        }
        return null;
    }
}
