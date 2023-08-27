<?php

namespace App\Repositories\Sex;

use App\Models\Sex;
use App\Repositories\Sex\SexRepositoryInterface;


class SexRepository implements SexRepositoryInterface
{
    private $model;
    private $field = ['id', 'name'];

    public function __construct(Sex $model)
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
        $sex = $this->model->where('id', $id)->first($this->field);
        return $sex ? $sex : $sex = null;
    }

    public function update($id, $data)
    {
        $sex = $this->model->find($id);
        if ($sex) {
            $sex->update($data);
            return $sex;
        }
        return null;
    }

    public function destroy($id)
    {
        $sex = $this->model->find($id);
        if ($sex) {
            $sex->delete();
            return $sex;
        }
        return null;
    }
}
