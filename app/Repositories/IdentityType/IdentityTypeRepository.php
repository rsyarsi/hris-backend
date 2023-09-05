<?php

namespace App\Repositories\IdentityType;

use App\Models\IdentityType;
use App\Repositories\IdentityType\IdentityTypeRepositoryInterface;


class IdentityTypeRepository implements IdentityTypeRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(IdentityType $model)
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
        $identitytype = $this->model->where('id', $id)->first($this->field);
        return $identitytype ? $identitytype : $identitytype = null;
    }

    public function update($id, $data)
    {
        $identitytype = $this->model->find($id);
        if ($identitytype) {
            $identitytype->update($data);
            return $identitytype;
        }
        return null;
    }

    public function destroy($id)
    {
        $identitytype = $this->model->find($id);
        if ($identitytype) {
            $identitytype->delete();
            return $identitytype;
        }
        return null;
    }
}
