<?php

namespace App\Repositories\SkillType;

use App\Models\SkillType;
use App\Repositories\SkillType\SkillTypeRepositoryInterface;


class SkillTypeRepository implements SkillTypeRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(SkillType $model)
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
        $skilltype = $this->model->where('id', $id)->first($this->field);
        return $skilltype ? $skilltype : $skilltype = null;
    }

    public function update($id, $data)
    {
        $skilltype = $this->model->find($id);
        if ($skilltype) {
            $skilltype->update($data);
            return $skilltype;
        }
        return null;
    }

    public function destroy($id)
    {
        $skilltype = $this->model->find($id);
        if ($skilltype) {
            $skilltype->delete();
            return $skilltype;
        }
        return null;
    }
}
