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
        'unit_id', 'department_id', 'started_at', 'employment_number',
        'resigned_at', 'user_id'
    ];

    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model->select($this->field);
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
                                $query->select('id', 'name');
                            },
                            'employeeOrganization' => function ($query) {
                                $query->select('id', 'employee_id', 'institution_name', 'position');
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
                                );
                            },
                            'employeeFamily' => function ($query) {
                                $query->select(
                                    'id',
                                    'employee_id',
                                    'name',
                                    'relationship_id',
                                    'as_emergency',
                                    'id_dead',
                                    'birth_date',
                                    'phone',
                                    'phone_country',
                                    'employer_familiescol',
                                    'address',
                                    'postal_code',
                                    'province_id',
                                    'city_id',
                                    'district_id',
                                    'village_id',
                                    'job_id'
                                );
                            },
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
}
