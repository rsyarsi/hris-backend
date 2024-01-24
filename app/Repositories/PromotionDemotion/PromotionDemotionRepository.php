<?php

namespace App\Repositories\PromotionDemotion;

use App\Models\PromotionDemotion;
use App\Services\Employee\EmployeeServiceInterface;
use App\Repositories\PromotionDemotion\PromotionDemotionRepositoryInterface;


class PromotionDemotionRepository implements PromotionDemotionRepositoryInterface
{
    private $model;
    private $employeeService;
    private $field = [
        'id',
        'user_created_id',
        'employee_id',
        'type',
        'unit_id',
        'before_position_id',
        'after_position_id',
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
        PromotionDemotion $model,
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
                        'unit' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'positionBefore' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'positionAfter' => function ($query) {
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
        $promotionDemotion = $this->model->create($data);
        $dataEmployee['position_id'] = $promotionDemotion->after_position_id;
        $dataEmployee['department_id'] = $promotionDemotion->department_id;
        $dataEmployee['shift_group_id'] = $promotionDemotion->shift_group_id;
        $dataEmployee['kabag_id'] = $promotionDemotion->kabag_id;
        $dataEmployee['supervisor_id'] = $promotionDemotion->supervisor_id;
        $dataEmployee['manager_id'] = $promotionDemotion->manager_id;
        $this->employeeService->updatePositionId($promotionDemotion->employee_id, $dataEmployee);
        return $promotionDemotion;
    }

    public function show($id)
    {
        $promotionDemotion = $this->model
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
                            'unit' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'positionBefore' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'positionAfter' => function ($query) {
                                $query->select('id', 'name');
                            },
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $promotionDemotion ? $promotionDemotion : $promotionDemotion = null;
    }

    public function update($id, $data)
    {
        $promotionDemotion = $this->model->find($id);
        if ($promotionDemotion) {
            $promotionDemotion->update($data);
            $dataEmployee['position_id'] = $promotionDemotion->after_position_id;
            $dataEmployee['department_id'] = $promotionDemotion->department_id;
            $dataEmployee['shift_group_id'] = $promotionDemotion->shift_group_id;
            $dataEmployee['kabag_id'] = $promotionDemotion->kabag_id;
            $dataEmployee['supervisor_id'] = $promotionDemotion->supervisor_id;
            $dataEmployee['manager_id'] = $promotionDemotion->manager_id;
            $this->employeeService->updatePositionId($promotionDemotion->employee_id, $dataEmployee);
            return $promotionDemotion;
        }
        return null;
    }

    public function destroy($id)
    {
        $promotionDemotion = $this->model->find($id);
        if ($promotionDemotion) {
            $promotionDemotion->delete();
            return $promotionDemotion;
        }
        return null;
    }

    public function promotionDemotionEmployee($perPage, $search = null)
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
