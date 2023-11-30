<?php

namespace App\Repositories\Deduction;

use App\Models\Deduction;
use App\Repositories\Deduction\DeductionRepositoryInterface;


class DeductionRepository implements DeductionRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'employee_id',
        'nilai',
        'keterangan',
        'tenor',
        'period',
        'pembayaran',
        'sisa',
        'kode_lunas',
    ];

    public function __construct(Deduction $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with(['employee' => function ($query)
                        {
                            $query->select('id', 'name', 'employment_number');
                        },
                    ])
                    ->select($this->field)
                    ->where(function ($query) use ($search) {
                        $query->where('period', 'like', "%{$search}%")
                            ->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->where('name', 'like', "%{$search}%");
                            });
                    });
        return $query->orderBy('period', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $position = $this->model
                        ->with(['employee' => function ($query)
                            {
                                $query->select('id', 'name', 'employment_number');
                            },
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $position ? $position : $position = null;
    }

    public function update($id, $data)
    {
        $position = $this->model->find($id);
        if ($position) {
            $position->update($data);
            return $position;
        }
        return null;
    }

    public function destroy($id)
    {
        $position = $this->model->find($id);
        if ($position) {
            $position->delete();
            return $position;
        }
        return null;
    }

    public function deductionEmployee($perPage, $search = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }
        $query = $this->model
                        ->select($this->field)
                        ->where(function ($query) use ($search) {
                            $query->where('period', 'like', "%{$search}%");
                        });
        $query->where('employee_id', $user->employee->id);
        return $query->orderBy('period', 'DESC')->paginate($perPage);
    }
}
