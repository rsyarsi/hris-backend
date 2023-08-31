<?php

namespace App\Repositories\LegalityType;

use App\Models\LegalityType;
use App\Repositories\LegalityType\LegalityTypeRepositoryInterface;


class LegalityTypeRepository implements LegalityTypeRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active', 'extended'];

    public function __construct(LegalityType $model)
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
        $legalitytype = $this->model->where('id', $id)->first($this->field);
        return $legalitytype ? $legalitytype : $legalitytype = null;
    }

    public function update($id, $data)
    {
        $legalitytype = $this->model->find($id);
        if ($legalitytype) {
            $legalitytype->update($data);
            return $legalitytype;
        }
        return null;
    }

    public function destroy($id)
    {
        $legalitytype = $this->model->find($id);
        if ($legalitytype) {
            $legalitytype->delete();
            return $legalitytype;
        }
        return null;
    }
}
