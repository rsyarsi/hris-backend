<?php

namespace App\Repositories\Department;

use App\Models\Department;
use App\Repositories\Department\DepartmentRepositoryInterface;


class DepartmentRepository implements DepartmentRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(Department $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model->select($this->field);
        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
        }
        return $query->orderBy('id', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $department = $this->model->where('id', $id)->first($this->field);
        return $department ? $department : $department = null;
    }

    public function update($id, $data)
    {
        $department = $this->model->find($id);
        if ($department) {
            $department->update($data);
            return $department;
        }
        return null;
    }

    public function destroy($id)
    {
        $department = $this->model->find($id);
        if ($department) {
            $department->delete();
            return $department;
        }
        return null;
    }
}
