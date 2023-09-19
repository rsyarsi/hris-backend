<?php

namespace App\Repositories\ShiftGroup;

use App\Models\ShiftGroup;
use App\Repositories\ShiftGroup\ShiftGroupRepositoryInterface;


class ShiftGroupRepository implements ShiftGroupRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'hour', 'day', 'type'];

    public function __construct(ShiftGroup $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model->select($this->field);
        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
        }
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $shiftgroup = $this->model->where('id', $id)->first($this->field);
        return $shiftgroup ? $shiftgroup : $shiftgroup = null;
    }

    public function update($id, $data)
    {
        $shiftgroup = $this->model->find($id);
        if ($shiftgroup) {
            $shiftgroup->update($data);
            return $shiftgroup;
        }
        return null;
    }

    public function destroy($id)
    {
        $shiftgroup = $this->model->find($id);
        if ($shiftgroup) {
            $shiftgroup->delete();
            return $shiftgroup;
        }
        return null;
    }
}
