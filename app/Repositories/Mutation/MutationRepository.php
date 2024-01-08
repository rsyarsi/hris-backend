<?php

namespace App\Repositories\Mutation;

use App\Models\Mutation;
use App\Repositories\Mutation\MutationRepositoryInterface;
use App\Services\Employee\EmployeeServiceInterface;

class MutationRepository implements MutationRepositoryInterface
{
    private $model;
    private $employeeService;
    private $field = [
        'id',
        'user_created_id',
        'employee_id',
        'before_unit_id',
        'after_unit_id',
        'date',
        'note',
        'no_sk',
    ];

    public function __construct(
        Mutation $model,
        EmployeeServiceInterface $employeeService,
    )
    {
        $this->model = $model;
        $this->employeeService = $employeeService;
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
                        'unitBefore' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'unitAfter' => function ($query) {
                            $query->select('id', 'name');
                        },
                    ])
                    ->select($this->field)
                    ->where(function ($query) use ($search) {
                        $query->where('date', 'like', "%{$search}%")
                            ->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
                                                ->orWhere('employment_number', 'like', '%' . $search . '%');
                            });
                    });
        return $query->orderBy('date', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        $mutation = $this->model->create($data);
        $this->employeeService->updateUnitId($mutation->employee_id, $mutation->after_unit_id);
        return $mutation;
    }

    public function show($id)
    {
        $mutation = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            },
                            'userCreated' => function ($query) {
                                $query->select('id', 'name', 'email');
                            },
                            'unitBefore' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'unitAfter' => function ($query) {
                                $query->select('id', 'name');
                            },
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $mutation ? $mutation : $mutation = null;
    }

    public function update($id, $data)
    {
        $mutation = $this->model->find($id);
        if ($mutation) {
            $mutation->update($data);
            $this->employeeService->updateUnitId($mutation->employee_id, $mutation->after_unit_id);
            return $mutation;
        }
        return null;
    }

    public function destroy($id)
    {
        $mutation = $this->model->find($id);
        if ($mutation) {
            $mutation->delete();
            return $mutation;
        }
        return null;
    }

    public function mutationEmployee($perPage, $search = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            $employeeId = null;
        }
        $employeeId = $user->employee->id;
        $query = $this->model
                        ->select($this->field)
                        ->where(function ($query) use ($search) {
                            $query->where('date', 'like', "%{$search}%");
                        });
        $query->where('employee_id', $employeeId);
        return $query->orderBy('date', 'DESC')->paginate($perPage);
    }
}
