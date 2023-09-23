<?php

namespace App\Repositories\OvertimeStatus;

use App\Models\OvertimeStatus;
use App\Repositories\OvertimeStatus\OvertimeStatusRepositoryInterface;


class OvertimeStatusRepository implements OvertimeStatusRepositoryInterface
{
    private $model;
    private $field = ['id', 'name'];

    public function __construct(OvertimeStatus $model)
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
        $overtimestatus = $this->model->where('id', $id)->first($this->field);
        return $overtimestatus ? $overtimestatus : $overtimestatus = null;
    }

    public function update($id, $data)
    {
        $overtimestatus = $this->model->find($id);
        if ($overtimestatus) {
            $overtimestatus->update($data);
            return $overtimestatus;
        }
        return null;
    }

    public function destroy($id)
    {
        $overtimestatus = $this->model->find($id);
        if ($overtimestatus) {
            $overtimestatus->delete();
            return $overtimestatus;
        }
        return null;
    }
}
