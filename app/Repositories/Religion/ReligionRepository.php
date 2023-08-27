<?php

namespace App\Repositories\Religion;

use App\Models\Religion;
use App\Repositories\Religion\ReligionRepositoryInterface;


class ReligionRepository implements ReligionRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(Religion $model)
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
        $religion = $this->model->where('id', $id)->first($this->field);
        return $religion ? $religion : $religion = null;
    }

    public function update($id, $data)
    {
        $religion = $this->model->find($id);
        if ($religion) {
            $religion->update($data);
            return $religion;
        }
        return null;
    }

    public function destroy($id)
    {
        $religion = $this->model->find($id);
        if ($religion) {
            $religion->delete();
            return $religion;
        }
        return null;
    }
}
