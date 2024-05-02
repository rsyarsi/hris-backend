<?php

namespace App\Repositories\Mutation;

use App\Models\Mutation;
use Illuminate\Support\Facades\DB;
use App\Services\Employee\EmployeeServiceInterface;
use App\Repositories\Mutation\MutationRepositoryInterface;

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
        'department_id',
        'shift_group_id',
        'kabag_id',
        'supervisor_id',
        'manager_id',
    ];

    public function __construct(
        Mutation $model,
        EmployeeServiceInterface $employeeService,
    ) {
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
                'department' => function ($query) {
                    $query->select('id', 'name');
                },
                'shiftGroup' => function ($query) {
                    $query->select('id', 'name');
                },
                'kabag' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'supervisor' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'manager' => function ($query) {
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
                        $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                            ->orWhere('employment_number', 'like', '%' . $search . '%');
                    });
            });
        return $query->orderBy('date', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        // Start the database transaction
        DB::beginTransaction();

        try {
            $mutation = $this->model->create($data);
            $dataEmployee['after_unit_id'] = $mutation->after_unit_id;
            $dataEmployee['department_id'] = $mutation->department_id;
            $dataEmployee['shift_group_id'] = $mutation->shift_group_id;
            $dataEmployee['kabag_id'] = $mutation->kabag_id;
            $dataEmployee['supervisor_id'] = $mutation->supervisor_id;
            $dataEmployee['manager_id'] = $mutation->manager_id;
            $this->employeeService->updateUnitId($mutation->employee_id, $dataEmployee);
            return $mutation;
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();

            // Handle the exception (log, throw, etc.)
            // For now, we'll just rethrow the exception
            throw $e;
        }
    }

    public function show($id)
    {
        $mutation = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'department' => function ($query) {
                    $query->select('id', 'name');
                },
                'shiftGroup' => function ($query) {
                    $query->select('id', 'name');
                },
                'kabag' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'supervisor' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'manager' => function ($query) {
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
        // Start the database transaction
        DB::beginTransaction();

        try {
            $mutation = $this->model->find($id);
            if ($mutation) {
                $mutation->update($data);
                $dataEmployee['after_unit_id'] = $mutation->after_unit_id;
                $dataEmployee['department_id'] = $mutation->department_id;
                $dataEmployee['shift_group_id'] = $mutation->shift_group_id;
                $dataEmployee['kabag_id'] = $mutation->kabag_id;
                $dataEmployee['supervisor_id'] = $mutation->supervisor_id;
                $dataEmployee['manager_id'] = $mutation->manager_id;
                $this->employeeService->updateUnitId($mutation->employee_id, $dataEmployee);
                // Commit the transaction
                DB::commit();
                return $mutation;
            }
            return null;
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();

            // Handle the exception (log, throw, etc.)
            // For now, we'll just rethrow the exception
            throw $e;
        }
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
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'department' => function ($query) {
                    $query->select('id', 'name');
                },
                'shiftGroup' => function ($query) {
                    $query->select('id', 'name');
                },
                'kabag' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'supervisor' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'manager' => function ($query) {
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
                $query->where('date', 'like', "%{$search}%");
            });
        $query->where('employee_id', $employeeId);
        return $query->orderBy('date', 'DESC')->paginate($perPage);
    }
}
