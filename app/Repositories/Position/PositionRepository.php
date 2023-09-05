<?php

namespace App\Repositories\Position;

use App\Models\Position;
use App\Repositories\Position\PositionRepositoryInterface;


class PositionRepository implements PositionRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(Position $model)
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
        $position = $this->model->where('id', $id)->first($this->field);
        return $position ? $position : $position = null;
    }

    public function update($id, $data)
    {
        $position = $this->model->find($id);
        if ($position) {
            $position->update($data);
            return $position;
        }
        return null;
    }

    public function destroy($id)
    {
        $position = $this->model->find($id);
        if ($position) {
            $position->delete();
            return $position;
        }
        return null;
    }
}
