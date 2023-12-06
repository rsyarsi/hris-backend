<?php

namespace App\Repositories\Ump;

use App\Models\Ump;
use App\Repositories\Ump\UmpRepositoryInterface;


class UmpRepository implements UmpRepositoryInterface
{
    private $model;
    private $field = ['id', 'year', 'nominal'];

    public function __construct(Ump $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model->select($this->field);
        if ($search !== null) {
            $query->whereRaw('LOWER(year) LIKE ?', ["%".strtolower($search)."%"]);
        }
        return $query->orderBy('year', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $ump = $this->model->where('id', $id)->first($this->field);
        return $ump ? $ump : $ump = null;
    }

    public function update($id, $data)
    {
        $ump = $this->model->find($id);
        if ($ump) {
            $ump->update($data);
            return $ump;
        }
        return null;
    }

    public function destroy($id)
    {
        $ump = $this->model->find($id);
        if ($ump) {
            $ump->delete();
            return $ump;
        }
        return null;
    }
}
