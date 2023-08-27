<?php

namespace App\Repositories\Unit;

use App\Models\Unit;
use App\Repositories\Unit\UnitRepositoryInterface;


class UnitRepository implements UnitRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(Unit $model)
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
        $unit = $this->model->where('id', $id)->first($this->field);
        return $unit ? $unit : $unit = null;
    }

    public function update($id, $data)
    {
        $unit = $this->model->find($id);
        if ($unit) {
            $unit->update($data);
            return $unit;
        }
        return null;
    }

    public function destroy($id)
    {
        $unit = $this->model->find($id);
        if ($unit) {
            $unit->delete();
            return $unit;
        }
        return null;
    }
}
