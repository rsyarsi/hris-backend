<?php

namespace App\Repositories\EmployeeContractDetail;

use App\Models\EmployeeContractDetail;
use App\Repositories\EmployeeContractDetail\EmployeeContractDetailRepositoryInterface;


class EmployeeContractDetailRepository implements EmployeeContractDetailRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'employee_contract_id',
        'payroll_component_id',
        'nominal',
        'active',
    ];

    public function __construct(EmployeeContractDetail $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                        ->with([
                            'employeeContract' => function ($query) {
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
                                    'shiftGroup:id,name,hour,day,type',
                                    'contractType:id,name,active',
                                    'unit:id,name,active',
                                    'position:id,name,active',
                                    'manager:id,name',
                                ]);
                            },
                            'payrollComponent' => function ($query) {
                                $query->select('id', 'name', 'active',);
                            },
                        ])
                        ->select($this->field);
        return $query->orderBy('id', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $employeecontractdetail = $this->model
                                        ->with([
                                            'employeeContract' => function ($query) {
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
                                                    'employee:id,name,employment_number',
                                                    'shiftGroup:id,name,hour,day,type',
                                                    'contractType:id,name,active',
                                                    'unit:id,name,active',
                                                    'position:id,name,active',
                                                    'manager:id,name',
                                                ]);
                                            },
                                            'payrollComponent' => function ($query) {
                                                $query->select('id', 'name', 'active',);
                                            },
                                        ])
                                        ->where('id', $id)
                                        ->first($this->field);
        return $employeecontractdetail ? $employeecontractdetail : $employeecontractdetail = null;
    }

    public function update($id, $data)
    {
        $employeecontractdetail = $this->model->find($id);
        if ($employeecontractdetail) {
            $employeecontractdetail->update($data);
            return $employeecontractdetail;
        }
        return null;
    }

    public function destroy($id)
    {
        $employeecontractdetail = $this->model->find($id);
        if ($employeecontractdetail) {
            $employeecontractdetail->delete();
            return $employeecontractdetail;
        }
        return null;
    }

    public function deleteByEmployeeContractId($employeeContractId)
    {
        return $this->model->where('employee_contract_id', $employeeContractId)->delete();
    }

    public function storeMultiple(array $data)
    {
        return $this->model->insert($data);
    }
}
