<?php

namespace App\Repositories\LeaveStatus;

use App\Models\LeaveStatus;
use App\Repositories\LeaveStatus\LeaveStatusRepositoryInterface;


class LeaveStatusRepository implements LeaveStatusRepositoryInterface
{
    private $model;
    private $field = ['id', 'name'];

    public function __construct(LeaveStatus $model)
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
        $leavestatus = $this->model->where('id', $id)->first($this->field);
        return $leavestatus ? $leavestatus : $leavestatus = null;
    }

    public function update($id, $data)
    {
        $leavestatus = $this->model->find($id);
        if ($leavestatus) {
            $leavestatus->update($data);
            return $leavestatus;
        }
        return null;
    }

    public function destroy($id)
    {
        $leavestatus = $this->model->find($id);
        if ($leavestatus) {
            $leavestatus->delete();
            return $leavestatus;
        }
        return null;
    }
}
