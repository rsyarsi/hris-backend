<?php

namespace App\Repositories\AdjustmentCuti;

use App\Models\AdjustmentCuti;
use App\Repositories\AdjustmentCuti\AdjustmentCutiRepositoryInterface;


class AdjustmentCutiRepository implements AdjustmentCutiRepositoryInterface
{
    private $model;
    private $field =
    [
        'id',
        'employee_id',
        'quantity_awal',
        'quantity_adjustment',
        'quantity_akhir',
        'year'
    ];

    public function __construct(AdjustmentCuti $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model->with([
            'employee' => function ($query) {
                $query->select('id', 'name', 'employment_number');
            },
        ])->select($this->field);
        if ($search !== null) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('year', 'like', '%' . $search . '%')
                    ->orWhere('employee_id', $search)
                    ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                        $employeeQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }
        return $query->orderBy('year', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $adjustmentcuti = $this->model
                                ->with([
                                    'employee' => function ($query) {
                                        $query->select('id', 'name', 'email', 'employment_number');
                                    },
                                ])
                                ->where('id', $id)
                                ->first($this->field);
        return $adjustmentcuti ? $adjustmentcuti : $adjustmentcuti = null;
    }

    public function update($id, $data)
    {
        $adjustmentcuti = $this->model->find($id);
        if ($adjustmentcuti) {
            $adjustmentcuti->update($data);
            return $adjustmentcuti;
        }
        return null;
    }

    public function destroy($id)
    {
        $adjustmentcuti = $this->model->find($id);
        if ($adjustmentcuti) {
            $adjustmentcuti->delete();
            return $adjustmentcuti;
        }
        return null;
    }

    public function adjustmentCutiEmployee($perPage, $search = null, $employeeId)
    {
        $query = $this->model->select($this->field);
        if ($search !== null) {
            $query->whereRaw('year LIKE ?', "%". $search ."%");
        }
        $query->where('employee_id', $employeeId);
        return $query->orderBy('year', 'DESC')->paginate($perPage);
    }
}
