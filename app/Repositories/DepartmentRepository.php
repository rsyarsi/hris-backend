<?php

namespace App\Repositories;

use App\Models\Department;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;


class DepartmentRepository implements DepartmentRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(Department $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model->orderBy('name', 'ASC')->get($this->field);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $department = $this->model->where('id', $id)->firstOrFail($this->field);
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
        } else {
            $product = null;
        }
        return $product;
    }
}
