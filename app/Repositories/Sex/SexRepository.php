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
