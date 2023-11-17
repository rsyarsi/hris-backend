<?php

namespace App\Repositories\Employee;

use App\Models\Employee;
use App\Repositories\Employee\EmployeeRepositoryInterface;


class EmployeeRepository implements EmployeeRepositoryInterface
{
    private $model;
    private $field =
    [
        'id', 'name', 'legal_identity_type_id', 'legal_identity_number', 'family_card_number',
        'sex_id','birth_place', 'birth_date', 'marital_status_id', 'religion_id', 'blood_type',
        'tax_identify_number', 'email', 'phone_number', 'phone_number_country', 'legal_address',
        'legal_postal_code', 'legal_province_id', 'legal_city_id', 'legal_district_id', 'legal_village_id',
        'legal_home_phone_number', 'legal_home_phone_country', 'current_address', 'current_postal_code',
        'current_province_id', 'current_city_id', 'current_district_id', 'current_village_id',
        'current_home_phone_number', 'current_home_phone_country', 'status_employment_id', 'position_id',
        'unit_id', 'department_id', 'started_at', 'employment_number', 'resigned_at', 'user_id', 'supervisor_id',
        'manager_id',
    ];

    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                        ->with([
                            'identityType' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'sex' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'maritalStatus' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'religion' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'province' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'city' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'district' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'village' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'statusEmployment' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'position' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'unit' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'department' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'manager' => function ($query) {
                                $query->select('id', 'name', 'email');
                            },
                            'supervisor' => function ($query) {
                                $query->select('id', 'name', 'email');
                            },
                            'user' => function ($query) {
                                $query->select('id', 'name', 'email')->with([
                                    'roles:id,name',
                                    'roles.permissions:id,name',
                                ]);
                            }
                        ])
                        ->select($this->field);
        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
        }
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $employee = $this->model
                        ->with([
                            'identityType' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'sex' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'maritalStatus' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'religion' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'province' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'city' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'district' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'village' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'statusEmployment' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'position' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'unit' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'department' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'user' => function ($query) {
                                $query->select('id', 'name')->with([
                                    'roles:id,name',
                                    'roles.permissions:id,name',
                                ]);
                            },
                            'employeeOrganization' => function ($query) {
                                $query->select('id', 'employee_id', 'institution_name', 'position', 'started_year', 'ended_year');
                            },
                            'employeeLegality' => function ($query) {
                                $query->select(
                                    'id',
                                    'employee_id',
                                    'legality_type_id',
                                    'started_at',
                                    'ended_at',
                                    'file_url',
                                    'file_path',
                                    'file_disk',
                                )->with('legalityType:id,name');
                            },
                            'employeeExperience' => function ($query) {
                                $query->select(
                                    'id',
                                    'employee_id',
                                    'company_name',
                                    'company_field',
                                    'responsibility',
                                    'started_at',
                                    'ended_at',
                                    'start_position',
                                    'end_position',
                                    'stop_reason',
                                    'latest_salary'
                                );
                            },
                            'employeeEducation' => function ($query) {
                                $query->select(
                                    'id',
                                    'employee_id',
                                    'education_id',
                                    'institution_name',
                                    'major',
                                    'started_year',
                                    'ended_year',
                                    'is_passed',
                                )->with('education:id,name');
                            },
                            'employeeFamily' => function ($query) {
                                $query->select(
                                    'id',
                                    'employee_id',
                                    'name',
                                    'relationship_id',
                                    'as_emergency',
                                    'is_dead',
                                    'birth_date',
                                    'phone',
                                    'phone_country',
                                    'address',
                                    'postal_code',
                                    'province_id',
                                    'city_id',
                                    'district_id',
                                    'village_id',
                                    'job_id',
                                )->with([
                                    'relationship:id,name',
                                    'job:id,name',
                                    'province:id,code,name',
                                    'city:id,code,province_code,name',
                                    'district:id,code,city_code,name',
                                    'village:id,code,district_code,name',
                                ]);
                            },
                            'employeeCertificate' => function ($query) {
                                $query->select(
                                    'id',
                                    'employee_id',
                                    'name',
                                    'institution_name',
                                    'started_at',
                                    'ended_at',
                                    'file_url',
                                    'file_path',
                                    'file_disk',
                                    'verified_at',
                                    'verified_user_Id',
                                    'is_extended'
                                );
                            },
                            'employeeSkill' => function ($query) {
                                $query->select(
                                    'id',
                                    'employee_id',
                                    'skill_type_id',
                                    'employee_certificate_id',
                                    'description',
                                    'level',
                                )->with([
                                    'skillType:id,name',
                                    'employeeCertificate:id,name,institution_name,started_at,ended_at,file_url,file_path,file_disk,verified_at,verified_user_Id,is_extended',
                                ]);
                            },
                            'contract' => function ($query) {
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
                                    'employeeContractDetail:id,employee_contract_id,payroll_component_id,nominal,active',
                                    'employeeContractDetail.payrollComponent:id,name,active',
                                ]);
                            }
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $employee ? $employee : $employee = null;
    }

    public function update($id, $data)
    {
        $employee = $this->model->find($id);
        if ($employee) {
            $employee->update($data);
            return $employee;
        }
        return null;
    }

    public function destroy($id)
    {
        $employee = $this->model->find($id);
        if ($employee) {
            $employee->delete();
            return $employee;
        }
        return null;
    }

    public function employeeNumberNull($perPage, $search = null)
    {
        $query = $this->model
                        ->with([
                            'identityType' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'sex' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'maritalStatus' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'religion' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'province' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'city' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'district' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'village' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'statusEmployment' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'position' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'unit' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'department' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'user' => function ($query) {
                                $query->select('id', 'name')->with([
                                    'roles:id,name',
                                    'roles.permissions:id,name',
                                ]);
                            }
                        ])
                        ->select($this->field);
        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
        }
        return $query
        ->whereNull('employment_number')
        ->paginate($perPage);
    }

    public function employeeEndContract($perPage, $search = null)
    {
        $today = now()->toDateString();
        $twoWeeksAgo = now()->subWeeks(2)->toDateString();

        $query = $this->model
            ->with([
                'contract' => function ($query) use ($today, $twoWeeksAgo) {
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
                        'employeeContractDetail:id,employee_contract_id,payroll_component_id,nominal,active',
                        'employeeContractDetail.payrollComponent:id,name,active',
                    ])->whereBetween('end_at', [$twoWeeksAgo, $today]);
                }
            ])
            ->select($this->field);

        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
        }

        return $query->paginate($perPage);
    }

    public function updateEmployeeContract($id, $data)
    {
        $employee = $this->model->find($id);
        if ($employee) {
            $employee->update([
                'employment_number' => $data['employment_number'],
                'unit_id' => $data['unit_id'],
                'position_id' => $data['position_id'],
                'department_id' => $data['department_id'],
                'manager_id' => $data['manager_id'],
                'started_at' => $data['started_at'],
            ]);
            return $employee;
        }
        return null;
    }

    public function updateUserId($id, $data)
    {
        $employee = $this->model->find($id);
        if ($employee) {
            $employee->update([
                'user_id' => $data['user_id'],
            ]);
            return $employee;
        }
        return null;
    }
}
