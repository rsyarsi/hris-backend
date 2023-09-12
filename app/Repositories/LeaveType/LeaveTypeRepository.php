<?php

namespace App\Repositories\LeaveType;

use App\Models\LeaveType;
use App\Repositories\LeaveType\LeaveTypeRepositoryInterface;


class LeaveTypeRepository implements LeaveTypeRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'is_salary_deduction', 'active'];

    public function __construct(LeaveType $model)
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
        $leavetype = $this->model->where('id', $id)->first($this->field);
        return $leavetype ? $leavetype : $leavetype = null;
    }

    public function update($id, $data)
    {
        $leavetype = $this->model->find($id);
        if ($leavetype) {
            $leavetype->update($data);
            return $leavetype;
        }
        return null;
    }

    public function destroy($id)
    {
        $leavetype = $this->model->find($id);
        if ($leavetype) {
            $leavetype->delete();
            return $leavetype;
        }
        return null;
    }
}
