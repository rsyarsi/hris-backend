<?php

namespace App\Repositories\Tax;

use App\Models\Tax;
use App\Repositories\Tax\TaxRepositoryInterface;


class TaxRepository implements TaxRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(Tax $model)
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
        $tax = $this->model->where('id', $id)->first($this->field);
        return $tax ? $tax : $tax = null;
    }

    public function update($id, $data)
    {
        $tax = $this->model->find($id);
        if ($tax) {
            $tax->update($data);
            return $tax;
        }
        return null;
    }

    public function destroy($id)
    {
        $tax = $this->model->find($id);
        if ($tax) {
            $tax->delete();
            return $tax;
        }
        return null;
    }
}
