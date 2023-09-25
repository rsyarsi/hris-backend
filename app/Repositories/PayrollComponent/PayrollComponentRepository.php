<?php

namespace App\Repositories\PayrollComponent;

use App\Models\PayrollComponent;
use App\Repositories\PayrollComponent\PayrollComponentRepositoryInterface;


class PayrollComponentRepository implements PayrollComponentRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(PayrollComponent $model)
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
        $payrollcomponent = $this->model->where('id', $id)->first($this->field);
        return $payrollcomponent ? $payrollcomponent : $payrollcomponent = null;
    }

    public function update($id, $data)
    {
        $payrollcomponent = $this->model->find($id);
        if ($payrollcomponent) {
            $payrollcomponent->update($data);
            return $payrollcomponent;
        }
        return null;
    }

    public function destroy($id)
    {
        $payrollcomponent = $this->model->find($id);
        if ($payrollcomponent) {
            $payrollcomponent->delete();
            return $payrollcomponent;
        }
        return null;
    }
}
