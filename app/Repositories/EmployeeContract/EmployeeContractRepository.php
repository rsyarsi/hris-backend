<?php

namespace App\Repositories\EmployeeContract;

use Carbon\Carbon;
use App\Models\EmployeeContract;
use App\Services\Helper\HelperService;
use App\Services\Employee\EmployeeService;
use App\Repositories\EmployeeContract\EmployeeContractRepositoryInterface;

class EmployeeContractRepository implements EmployeeContractRepositoryInterface
{
    private $model;
    private $employeeService;
    private $helperService;

    private $field = [
        'id',
        'employee_id',
        'transaction_number',
        'start_at',
        'end_at',
        'sk_number',
        'shift_group_id',
        'umk',
        'contract_type_id',
        'day',
        'hour',
        'hour_per_day',
        'istirahat_overtime',
        'vot1',
        'vot2',
        'vot3',
        'vot4',
        'unit_id',
        'position_id',
        'manager_id',
    ];

    public function __construct(EmployeeContract $model, EmployeeService $employeeService, HelperService $helperService)
    {
        $this->model = $model;
        $this->employeeService = $employeeService;
        $this->helperService = $helperService;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            },
                            'shiftGroup' => function ($query) {
                                $query->select('id', 'name', 'hour', 'day', 'type');
                            },
                            'contractType' => function ($query) {
                                $query->select('id', 'name', 'active');
                            },
                            'unit' => function ($query) {
                                $query->select('id', 'name', 'active');
                            },
                            'position' => function ($query) {
                                $query->select('id', 'name', 'active');
                            },
                            'manager' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            },
                            'employeeContractDetail' => function ($query) {
                                $query->select(
                                    'id',
                                    'employee_contract_id',
                                    'payroll_component_id',
                                    'nominal',
                                    'active'
                                );
                            },
                        ])->select($this->field);
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        $id = $data['employee_id'];
        $latestEmployeeNumber = $this->helperService->show();
        $employee = $this->employeeService->show($id);
        if ($employee) {
            if ($employee->employment_number === null) {
                $nowYear = now()->format('y');
                $birthYear = Carbon::parse($employee->birth_date)->format('y');
                $nextEmployeeNumber = str_pad($latestEmployeeNumber->employment_number + 1, 4, '0', STR_PAD_LEFT);
                $employeNumber = "03{$nowYear}{$birthYear}{$nextEmployeeNumber}";
                $dataContract['employment_number'] = $employeNumber;
                // Increment and update the employment_number in the helpers table
                $latestEmployeeNumber->increment('employment_number');
                $latestEmployeeNumber->save();
            } else {
                $dataContract['employment_number'] = $employee->employment_number;
            }
            $dataContract['started_at'] = $data['start_at'];
            $dataContract['unit_id'] = $data['unit_id'];
            $dataContract['position_id'] = $data['position_id'];
            $dataContract['manager_id'] = $data['manager_id'];
            $this->employeeService->updateEmployeeContract($id, $dataContract);
        }
        return $this->model->create($data);
    }

    public function show($id)
    {
        $employeeContract = $this->model
                                    ->with([
                                        'employee' => function ($query) {
                                            $query->select('id', 'name', 'employment_number');
                                        },
                                        'shiftGroup' => function ($query) {
                                            $query->select('id', 'name', 'hour', 'day', 'type');
                                        },
                                        'contractType' => function ($query) {
                                            $query->select('id', 'name', 'active');
                                        },
                                        'unit' => function ($query) {
                                            $query->select('id', 'name', 'active');
                                        },
                                        'position' => function ($query) {
                                            $query->select('id', 'name', 'active');
                                        },
                                        'manager' => function ($query) {
                                            $query->select('id', 'name', 'employment_number');
                                        },
                                        'employeeContractDetail' => function ($query) {
                                            $query->select(
                                                'id',
                                                'employee_contract_id',
                                                'payroll_component_id',
                                                'nominal',
                                                'active'
                                            );
                                        },
                                    ])
                                    ->where('id', $id)
                                    ->first($this->field);
        return $employeeContract ? $employeeContract : $employeeContract = null;
    }

    public function update($id, $data)
    {
        $employeeContract = $this->model->find($id);
        $employeeId = $data['employee_id'];
        $employee = $this->employeeService->show($employeeId);
        if ($employeeContract) {
            $dataContract['employment_number'] = $employee->employment_number;
            $dataContract['started_at'] = $employee->started_at;
            $dataContract['unit_id'] = $data['unit_id'];
            $dataContract['position_id'] = $data['position_id'];
            $dataContract['manager_id'] = $data['manager_id'];
            $this->employeeService->updateEmployeeContract($employeeId, $dataContract);
            $employeeContract->update($data);
            return $employeeContract;
        }
        return null;
    }

    public function destroy($id)
    {
        $employeeContract = $this->model->find($id);
        if ($employeeContract) {
            $employeeContract->delete();
            return $employeeContract;
        }
        return null;
    }

    public function getLastTransactionNumber()
    {
        return $this->model->select('transaction_number')
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
