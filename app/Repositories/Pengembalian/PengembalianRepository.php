<?php

namespace App\Repositories\Pengembalian;

use App\Models\Pengembalian;
use App\Repositories\Pengembalian\PengembalianRepositoryInterface;
use App\Services\Employee\EmployeeServiceInterface;

class PengembalianRepository implements PengembalianRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'employee_id',
        'payroll_period',
        'amount',
        'user_created_id',
    ];

    public function __construct(Pengembalian $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name', 'employment_number');
                        },
                        'userCreated' => function ($query) {
                            $query->select('id', 'name', 'email');
                        },
                    ])
                    ->select($this->field)
                    ->where(function ($query) use ($search) {
                        $query->where('payroll_period', 'like', "%{$search}%")
                            ->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
                                                ->orWhere('employment_number', 'like', '%' . $search . '%');
                            });
                    });
        return $query->orderBy('created_at', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);

    }

    public function show($id)
    {
        $pengembalian = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            },
                            'userCreated' => function ($query) {
                                $query->select('id', 'name', 'email');
                            },
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $pengembalian ? $pengembalian : $pengembalian = null;
    }

    public function update($id, $data)
    {
        $pengembalian = $this->model->find($id);
        if ($pengembalian) {
            $pengembalian->update($data);
            return $pengembalian;
        }
        return null;
    }

    public function destroy($id)
    {
        $pengembalian = $this->model->find($id);
        if ($pengembalian) {
            $pengembalian->delete();
            return $pengembalian;
        }
        return null;
    }

    public function pengembalianEmployee($perPage, $search = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            $employeeId = null;
        }
        $employeeId = $user->employee->id;
        $query = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            },
                            'userCreated' => function ($query) {
                                $query->select('id', 'name', 'email');
                            },
                        ])
                        ->select($this->field)
                        ->where(function ($query) use ($search) {
                            $query->where('period_payroll', 'like', "%{$search}%");
                        });
        $query->where('employee_id', $employeeId);
        return $query->orderBy('date', 'created_at')->paginate($perPage);
    }
}
