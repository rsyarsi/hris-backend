<?php

namespace App\Repositories\Pph;

use App\Models\Pph;
use App\Repositories\Pph\PphRepositoryInterface;


class PphRepository implements PphRepositoryInterface
{
    private $model;
    private $field = ['id', 'employee_id', 'nilai', 'period'];

    public function __construct(Pph $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with(['employee' => function ($query) {
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
        $position = $this->model->where('id', $id)->first($this->field);
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

    public function pphEmployee($perPage, $search = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }
        $query = $this->model->select($this->field)
        ->where(function ($query) use ($search) {
            $query->where('period', 'like', "%{$search}%");
        });
        $query->where('employee_id', $user->employee->id);
        return $query->orderBy('period', 'DESC')->paginate($perPage);
    }
}
