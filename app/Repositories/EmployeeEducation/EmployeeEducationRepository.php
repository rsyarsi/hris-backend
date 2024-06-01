<?php

namespace App\Repositories\EmployeeEducation;

use App\Models\EmployeeEducation;
use App\Repositories\EmployeeEducation\EmployeeEducationRepositoryInterface;


class EmployeeEducationRepository implements EmployeeEducationRepositoryInterface
{
    private $model;

    public function __construct(EmployeeEducation $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
            ->with([
                'employee:id,name,employment_number',
                'education:id,name',
            ]);
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
        $employeeeducation = $this->model
            ->with([
                'employee:id,name,employment_number',
                'education:id,name',
            ])
            ->where('id', $id)
            ->first();
        return $employeeeducation ? $employeeeducation : $employeeeducation = null;
    }

    public function update($id, $data)
    {
        $employeeeducation = $this->model->find($id);
        if ($employeeeducation) {
            $employeeeducation->update($data);
            return $employeeeducation;
        }
        return null;
    }

    public function destroy($id)
    {
        $employeeeducation = $this->model->find($id);
        if ($employeeeducation) {
            $employeeeducation->delete();
            return $employeeeducation;
        }
        return null;
    }
}
