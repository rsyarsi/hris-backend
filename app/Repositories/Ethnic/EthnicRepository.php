<?php

namespace App\Repositories\Ethnic;

use App\Models\Ethnic;
use App\Repositories\Ethnic\EthnicRepositoryInterface;


class EthnicRepository implements EthnicRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(Ethnic $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model->select($this->field);
        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
        }
        return $query->orderBy('name', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $ethnic = $this->model->where('id', $id)->first($this->field);
        return $ethnic ? $ethnic : $ethnic = null;
    }

    public function update($id, $data)
    {
        $ethnic = $this->model->find($id);
        if ($ethnic) {
            $ethnic->update($data);
            return $ethnic;
        }
        return null;
    }

    public function destroy($id)
    {
        $ethnic = $this->model->find($id);
        if ($ethnic) {
            $ethnic->delete();
            return $ethnic;
        }
        return null;
    }
}
