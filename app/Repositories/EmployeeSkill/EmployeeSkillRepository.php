<?php

namespace App\Repositories\EmployeeSkill;

use App\Models\EmployeeSkill;
use App\Repositories\EmployeeSkill\EmployeeSkillRepositoryInterface;


class EmployeeSkillRepository implements EmployeeSkillRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'employee_id',
        'skill_type_id',
        'employee_certificate_id',
        'description',
        'level',
    ];

    public function __construct(EmployeeSkill $model)
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
                        'skillType' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'employeeCertificate' => function ($query) {
                            $query->select(
                                'id',
                                'employee_id',
                                'name',
                                'institution_name',
                                'started_at',
                                'ended_at',
                                'file_url',
                                'file_path',
                                'file_disk',
                                'verified_at',
                                'verified_user_Id',
                                'is_extended'
                            );
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
        $employeeskill = $this->model
                                    ->with([
                                        'employee' => function ($query) {
                                            $query->select('id', 'name', 'employment_number');
                                        },
                                    ])
                                    ->where('id', $id)
                                    ->first($this->field);
        return $employeeskill ? $employeeskill : $employeeskill = null;
    }

    public function update($id, $data)
    {
        $employeeskill = $this->model->find($id);
        if ($employeeskill) {
            $employeeskill->update($data);
            return $employeeskill;
        }
        return null;
    }

    public function destroy($id)
    {
        $employeeskill = $this->model->find($id);
        if ($employeeskill) {
            $employeeskill->delete();
            return $employeeskill;
        }
        return null;
    }
}
