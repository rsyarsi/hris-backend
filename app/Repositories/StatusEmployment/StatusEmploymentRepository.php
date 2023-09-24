<?php

namespace App\Repositories\StatusEmployment;

use App\Models\StatusEmployment;
use App\Repositories\StatusEmployment\StatusEmploymentRepositoryInterface;


class StatusEmploymentRepository implements StatusEmploymentRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(StatusEmployment $model)
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
        $statusemployment = $this->model->where('id', $id)->first($this->field);
        return $statusemployment ? $statusemployment : $statusemployment = null;
    }

    public function update($id, $data)
    {
        $statusemployment = $this->model->find($id);
        if ($statusemployment) {
            $statusemployment->update($data);
            return $statusemployment;
        }
        return null;
    }

    public function destroy($id)
    {
        $statusemployment = $this->model->find($id);
        if ($statusemployment) {
            $statusemployment->delete();
            return $statusemployment;
        }
        return null;
    }
}
