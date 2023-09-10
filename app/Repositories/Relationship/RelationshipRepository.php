<?php

namespace App\Repositories\Relationship;

use App\Models\Relationship;
use App\Repositories\Relationship\RelationshipRepositoryInterface;


class RelationshipRepository implements RelationshipRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(Relationship $model)
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
        $relationship = $this->model->where('id', $id)->first($this->field);
        return $relationship ? $relationship : $relationship = null;
    }

    public function update($id, $data)
    {
        $relationship = $this->model->find($id);
        if ($relationship) {
            $relationship->update($data);
            return $relationship;
        }
        return null;
    }

    public function destroy($id)
    {
        $relationship = $this->model->find($id);
        if ($relationship) {
            $relationship->delete();
            return $relationship;
        }
        return null;
    }
}
