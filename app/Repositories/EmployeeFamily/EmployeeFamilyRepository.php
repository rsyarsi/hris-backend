<?php

namespace App\Repositories\EmployeeFamily;

use App\Models\EmployeeFamily;
use App\Repositories\EmployeeFamily\EmployeeFamilyRepositoryInterface;


class EmployeeFamilyRepository implements EmployeeFamilyRepositoryInterface
{
    private $model;
    private $field =
    [
        'employee_id',
        'name',
        'relationship_id',
        'as_emergency',
        'is_dead',
        'birth_date',
        'phone',
        'phone_country',
        'address',
        'postal_code',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'job_id',
    ];

    public function __construct(EmployeeFamily $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model->select($this->field);
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
                            });
            });
        }
        return $query->orderBy('id', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $employeeFamily = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'relationship' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'job' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'province' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'city' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'district' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'village' => function ($query) {
                                $query->select('id', 'name');
                            }
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $employeeFamily ? $employeeFamily : $employeeFamily = null;
    }

    public function update($id, $data)
    {
        $employeeFamily = $this->model->find($id);
        if ($employeeFamily) {
            $employeeFamily->update($data);
            return $employeeFamily;
        }
        return null;
    }

    public function destroy($id)
    {
        $employeeFamily = $this->model->find($id);
        if ($employeeFamily) {
            $employeeFamily->delete();
            return $employeeFamily;
        }
        return null;
    }
}
