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
