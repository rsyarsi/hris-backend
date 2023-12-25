<?php

namespace App\Repositories\EmployeeContract;

use App\Models\Employee;
use Carbon\Carbon;
use App\Models\EmployeeContract;
use App\Models\EmployeeContractDetail;
use App\Services\Helper\HelperServiceInterface;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\PayrollComponent\PayrollComponentServiceInterface;
use App\Repositories\EmployeeContract\EmployeeContractRepositoryInterface;
use App\Services\EmployeeContractDetail\EmployeeContractDetailServiceInterface;

class EmployeeContractRepository implements EmployeeContractRepositoryInterface
{
    private $model;
    private $employeeService;
    private $helperService;
    private $contractDetailService;
    private $payrollComponentService;

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
        'department_id',
        'supervisor_id',
        'kabag_id',
    ];

    public function __construct(
        EmployeeContract $model,
        EmployeeServiceInterface $employeeService,
        HelperServiceInterface $helperService,
        EmployeeContractDetailServiceInterface $contractDetailService,
        PayrollComponentServiceInterface $payrollComponentService,
    )
    {
        $this->model = $model;
        $this->employeeService = $employeeService;
        $this->helperService = $helperService;
        $this->contractDetailService = $contractDetailService;
        $this->payrollComponentService = $payrollComponentService;
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
                            'department' => function ($query) {
                                $query->select('id', 'name', 'active');
                            },
                            'manager' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            },
                            'supervisor' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            },
                            'kabag' => function ($query) {
                                $query->select('id', 'name', 'email');
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
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
                                                ->orWhere('employment_number', 'like', '%' . $search . '%');
                            });
            });
        }
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
            $dataContract['started_at'] = $data['start_at'] ?? null;
            $dataContract['unit_id'] = $data['unit_id'] ?? null;
            $dataContract['position_id'] = $data['position_id'] ?? null;
            $dataContract['department_id'] = $data['department_id'] ?? null;
            $dataContract['manager_id'] = $data['manager_id'] ?? null;
            $dataContract['supervisor_id'] = $data['supervisor_id'] ?? null;
            $dataContract['shift_group_id'] = $data['shift_group_id'] ?? null;
            $dataContract['kabag_id'] = $data['kabag_id'] ?? null;
            $dataContract['status_employee'] = $data['status_employee'] ?? null;
            $this->employeeService->updateEmployeeContract($id, $dataContract);
        }
        $createdData = $this->model->create($data);

        $existingRecordContractDetail = EmployeeContractDetail::where('employee_contract_id', $createdData->id)->first();
        if (!$existingRecordContractDetail) {
            // insert all component payroll in new contract
            $componentPayroll = $this->payrollComponentService->index(1000, null);
            foreach ($componentPayroll as $item) {
                $value['payroll_component_id'] = $item->id;
                $value['employee_contract_id'] = $createdData->id;
                $value['nominal'] = 0;
                $value['active'] = 0;
                $this->contractDetailService->storeMultiple($value);
            }
        }
        return $createdData;
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
                                        'department' => function ($query) {
                                            $query->select('id', 'name', 'active');
                                        },
                                        'manager' => function ($query) {
                                            $query->select('id', 'name', 'employment_number');
                                        },
                                        'supervisor' => function ($query) {
                                            $query->select('id', 'name', 'employment_number');
                                        },
                                        'kabag' => function ($query) {
                                            $query->select('id', 'name', 'email');
                                        },
                                        'employeeContractDetail' => function ($query) {
                                            $query->select(
                                                'id',
                                                'employee_contract_id',
                                                'payroll_component_id',
                                                'nominal',
                                                'active'
                                            )->with('payrollComponent:id,name');
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
            $dataContract['unit_id'] = $data['unit_id'] ?? null;
            $dataContract['position_id'] = $data['position_id'] ?? null;
            $dataContract['department_id'] = $data['department_id'] ?? null;
            $dataContract['manager_id'] = $data['manager_id'] ?? null;
            $dataContract['supervisor_id'] = $data['supervisor_id'] ?? null;
            $dataContract['shift_group_id'] = $data['shift_group_id'] ?? null;
            $dataContract['kabag_id'] = $data['kabag_id'] ?? null;
            $dataContract['status_employee'] = $data['status_employee'] ?? null;
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
            $this->contractDetailService->deleteByEmployeeContractId($id);
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

    public function countEmployeeEndContract()
    {
        $today = Carbon::now()->toDateString();
        $twoWeeksLater = Carbon::now()->addWeeks(2)->toDateString();

        $count = Employee::with([
                                'contract' => function ($query) use ($today, $twoWeeksLater) {
                                    $query->select(
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
                                    )->with([
                                        'employee:id,name',
                                        'employeeContractDetail:id,employee_contract_id,payroll_component_id,nominal,active','employeeContractDetail.payrollComponent:id,name,active',
                                    ])->latest()->first();
                                }
                            ])
                            ->select($this->field)
                            ->whereHas('contract', function ($contractQuery) use ($today, $twoWeeksLater) {
                                $contractQuery->where('end_at', '<=', $today)
                                                ->orWhereBetween('end_at', [$today, $twoWeeksLater])->latest();
                            });

        return [
            'count' => $count->count()
        ];
    }
}
