<?php

namespace App\Repositories\AdjustmentCuti;

use App\Models\AdjustmentCuti;
use App\Services\CatatanCuti\CatatanCutiServiceInterface;
use App\Repositories\AdjustmentCuti\AdjustmentCutiRepositoryInterface;


class AdjustmentCutiRepository implements AdjustmentCutiRepositoryInterface
{
    private $model;
    private $catatanCutiService;
    private $field =
    [
        'id',
        'employee_id',
        'quantity_awal',
        'quantity_adjustment',
        'quantity_akhir',
        'year',
        'description'
    ];

    public function __construct(AdjustmentCuti $model, CatatanCutiServiceInterface $catatanCutiService)
    {
        $this->model = $model;
        $this->catatanCutiService = $catatanCutiService;
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
                        $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
                                        ->orWhere('employment_number', 'like', '%' . $search . '%');
                    });
            });
        }
        return $query->orderBy('year', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        $adjustmentCuti = $this->model->create($data);
        $catatanCutiData = [
            'adjustment_cuti_id' => $adjustmentCuti->id,
            'leave_id' => null,
            'employee_id' => $adjustmentCuti->employee_id,
            'quantity_awal' => $adjustmentCuti->quantity_awal,
            'quantity_akhir' => $adjustmentCuti->quantity_akhir,
            'quantity_in' => 0,
            'quantity_out' => 0,
            'type' => 'ADJUSTMENT CUTI',
            'description' => $adjustmentCuti->description,
            'batal' => 0,
        ];
        $this->catatanCutiService->store($catatanCutiData);
        return $adjustmentCuti;
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
        $adjustmentCuti = $this->model->find($id);
        if ($adjustmentCuti) {
            $adjustmentCuti->update($data);
            $catatanCutiData = [
                'adjustment_cuti_id' => $adjustmentCuti->id,
                'leave_id' => null,
                'employee_id' => $adjustmentCuti->employee_id,
                'quantity_awal' => $adjustmentCuti->quantity_awal,
                'quantity_akhir' => $adjustmentCuti->quantity_akhir,
                'quantity_in' => 0,
                'quantity_out' => 0,
                'type' => 'ADJUSTMENT CUTI',
                'description' => $adjustmentCuti->description,
                'batal' => 0,
            ];
            $this->catatanCutiService->store($catatanCutiData);
            return $adjustmentCuti;
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
        $query->where('employee_id', $employeeId);
        return $query->orderBy('id', 'DESC')->paginate($perPage);
    }
}
