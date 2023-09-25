<?php

namespace App\Repositories\ContractType;

use App\Models\ContractType;
use App\Repositories\ContractType\ContractTypeRepositoryInterface;


class ContractTypeRepository implements ContractTypeRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(ContractType $model)
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
        $contracttype = $this->model->where('id', $id)->first($this->field);
        return $contracttype ? $contracttype : $contracttype = null;
    }

    public function update($id, $data)
    {
        $contracttype = $this->model->find($id);
        if ($contracttype) {
            $contracttype->update($data);
            return $contracttype;
        }
        return null;
    }

    public function destroy($id)
    {
        $contracttype = $this->model->find($id);
        if ($contracttype) {
            $contracttype->delete();
            return $contracttype;
        }
        return null;
    }
}
