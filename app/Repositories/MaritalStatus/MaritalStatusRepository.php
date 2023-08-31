<?php

namespace App\Repositories\MaritalStatus;

use App\Models\MaritalStatus;
use App\Repositories\MaritalStatus\MaritalStatusRepositoryInterface;


class MaritalStatusRepository implements MaritalStatusRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(MaritalStatus $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model->orderBy('id', 'ASC')->get($this->field);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $maritalstatus = $this->model->where('id', $id)->first($this->field);
        return $maritalstatus ? $maritalstatus : $maritalstatus = null;
    }

    public function update($id, $data)
    {
        $maritalstatus = $this->model->find($id);
        if ($maritalstatus) {
            $maritalstatus->update($data);
            return $maritalstatus;
        }
        return null;
    }

    public function destroy($id)
    {
        $maritalstatus = $this->model->find($id);
        if ($maritalstatus) {
            $maritalstatus->delete();
            return $maritalstatus;
        }
        return null;
    }
}
