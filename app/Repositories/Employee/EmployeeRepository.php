<?php

namespace App\Repositories\Employee;

use Carbon\Carbon;
use App\Models\{Employee, User};
use Illuminate\Support\Facades\DB;
use App\Repositories\Employee\EmployeeRepositoryInterface;


class EmployeeRepository implements EmployeeRepositoryInterface
{
    private $model;
    private $field =
    [
        'id', 'name', 'legal_identity_type_id', 'legal_identity_number', 'family_card_number',
        'sex_id', 'birth_place', 'birth_date', 'marital_status_id', 'religion_id', 'blood_type',
        'tax_identify_number', 'email', 'phone_number', 'phone_number_country', 'legal_address',
        'legal_postal_code', 'legal_province_id', 'legal_city_id', 'legal_district_id', 'legal_village_id',
        'legal_home_phone_number', 'legal_home_phone_country', 'current_address', 'current_postal_code',
        'current_province_id', 'current_city_id', 'current_district_id', 'current_village_id',
        'current_home_phone_number', 'current_home_phone_country', 'status_employment_id', 'position_id',
        'unit_id', 'department_id', 'started_at', 'employment_number', 'resigned_at', 'user_id', 'supervisor_id',
        'manager_id', 'pin', 'shift_group_id', 'kabag_id', 'rekening_number', 'status_employee', 'bpjs_number',
        'bpjstk_number', 'file_url', 'file_path', 'file_disk',
    ];

    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null, $active)
    {
        $query = $this->model
            ->with([
                'identityType:id,name',
                'sex:id,name',
                'maritalStatus:id,name',
                'religion:id,name',
                'province:id,name',
                'city:id,name',
                'district:id,name',
                'village:id,name',
                'currentProvince:id,name',
                'currentCity:id,name',
                'currentDistrict:id,name',
                'currentVillage:id,name',
                'statusEmployment:id,name',
                'position:id,name',
                'unit:id,name',
                'department:id,name',
                'shiftGroup:id,name',
                'manager:id,name,email',
                'supervisor:id,name,email',
                'kabag:id,name,email',
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'username', 'firebase_id', 'active')->with([
                        'roles:id,name',
                        'roles.permissions:id,name',
                    ]);
                },
                'contract' => function ($query) {
                    $query->select('id', 'employee_id', 'transaction_number', 'start_at', 'end_at')->with([
                        'employeeContractDetail:id,employee_contract_id,nominal',
                    ]);
                }
            ])
            ->select($this->field);

        if ($active === true) {
            $query->where('resigned_at', '>=', Carbon::now()->toDateString());
        }

        if ($search !== null) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                    ->orWhere('employment_number', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        return $query->orderBy('employment_number', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function employeeProfileMobile($employeeId)
    {
        $employee = DB::table('employees')
            ->select(
                'employees.id as employee_id',
                'employees.employment_number as nik',
                'employees.name as employee_name',
                'employees.birth_date as tanggal_lahir',
                'employees.started_at as tanggal_masuk',
                // 'employees.started_at as lama_kerja',
                DB::raw('EXTRACT(DAY FROM AGE(current_date, employees.started_at)) as lama_kerja'),
                'employees.file_url as photo',
                'munits.name as unit_name',
                'msexs.name as jenis_kelamin'
            )
            ->leftJoin('munits', 'employees.unit_id', '=', 'munits.id')
            ->leftJoin('msexs', 'employees.sex_id', '=', 'msexs.id')
            ->where('employees.id', $employeeId)
            ->first();
        if ($employee) {
            return [
                'message' => 'Employee Retrieved Successfully!',
                'success' => true,
                'code' => 200,
                'data' => [$employee]
            ];
        }
        return [
            'message' => 'Employee Retrieved Successfully!',
            'success' => false,
            'code' => 200,
            'data' => ''
        ];
    }

    public function show($id)
    {
        $employee = $this->model
            ->with([
                'identityType:id,name',
                'sex:id,name',
                'maritalStatus:id,name',
                'religion:id,name',
                'province:id,name',
                'city:id,name',
                'district:id,name',
                'village:id,name',
                'currentProvince:id,name',
                'currentCity:id,name',
                'currentDistrict:id,name',
                'currentVillage:id,name',
                'statusEmployment:id,name',
                'position:id,name',
                'unit:id,name',
                'department:id,name',
                'shiftGroup:id,name',
                'manager' => function ($query) {
                    $query->select('id', 'name', 'email', 'user_id')->with('user:id,email,username,firebase_id');
                },
                'supervisor' => function ($query) {
                    $query->select('id', 'name', 'email', 'user_id')->with('user:id,email,username,firebase_id');
                },
                'kabag' => function ($query) {
                    $query->select('id', 'name', 'email', 'user_id')->with('user:id,email,username,firebase_id');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'username', 'firebase_id', 'active')->with([
                        'roles:id,name',
                        'roles.permissions:id,name',
                    ]);
                },
                'employeeOrganization:id,employee_id,institution_name,position,started_year,ended_year',
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
                'employeeExperience:id,employee_id,company_name,company_field,responsibility,started_at,ended_at,start_position,end_position,stop_reason,latest_salary',
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
                'employeeCertificate:id,employee_id,name,institution_name,started_at,ended_at,file_url,file_path,file_disk,verified_at,verified_user_Id,is_extended',
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

    public function checkNameEmail($name, $email)
    {
        return $this->model->where('name', $name)->orWhere('email', $email)->exists();
    }

    public function update($id, $data)
    {
        $employee = $this->model->find($id);
        if ($employee) {
            $employee->update($data);
            // if ($employee->resigned_at , '>=', Carbon::now()->toDateString()) {
            //     User::where('id', $employee->user_id)->update(['active' => 0]);
            // }
            return $employee;
        }
        return null;
    }

    public function employeeUploadPhoto($id, $data)
    {
        $employee = $this->model->find($id);
        if ($employee) {
            $employee->update($data);
            return $employee;
        }
        return null;
    }

    public function employeeUploadPhotoMobile($data)
    {
        $employee = $this->model->where('id', $data['employee_id'])->first();
        if ($employee) {
            $employee->update($data);
            return [
                'message' => 'Photo Uploaded successfully!',
                'success' => true,
                'code' => 200,
                'data' => [$employee]
            ];
        }
        return [
            'message' => 'Photo Gagal Terupload!',
            'success' => false,
            'code' => 200,
            'data' => 'Gagal Terupload!'
        ];
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
                'currentProvince' => function ($query) {
                    $query->select('id', 'name');
                },
                'currentCity' => function ($query) {
                    $query->select('id', 'name');
                },
                'currentDistrict' => function ($query) {
                    $query->select('id', 'name');
                },
                'currentVillage' => function ($query) {
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
                'shiftGroup' => function ($query) {
                    $query->select('id', 'name');
                },
                'manager' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'supervisor' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'kabag' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'username', 'firebase_id', 'active')->with([
                        'roles:id,name',
                        'roles.permissions:id,name',
                    ]);
                },
            ])
            ->select($this->field);
        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"]);
        }
        return $query->whereNull('employment_number')->paginate($perPage);
    }

    public function employeeEndContract($perPage, $search = null)
    {
        $today = Carbon::now()->toDateString();
        $threeMonth = Carbon::now()->addMonth(3)->toDateString();

        $query = $this->model
            ->with([
                'contract' => function ($query) use ($today, $threeMonth) {
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
                        'employeeContractDetail:id,employee_contract_id,payroll_component_id,nominal,active', 'employeeContractDetail.payrollComponent:id,name,active',
                    ])->latest()->first();
                }
            ])
            ->select($this->field);
        // ->whereHas('contract', function ($contractQuery) use ($today, $threeMonth) {
        //     $contractQuery->where('end_at', '<=', $today)
        //                     ->orWhereBetween('end_at', [$today, $threeMonth])->latest();
        // });

        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"]);
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
                'supervisor_id' => $data['supervisor_id'],
                'started_at' => $data['started_at'],
                'shift_group_id' => $data['shift_group_id'],
                'kabag_id' => $data['kabag_id'],
                'status_employee' => $data['status_employee'],
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
                'user_id' => $data,
            ]);
            return $employee;
        }
        return null;
    }

    public function updateUnitId($id, $data)
    {
        $employee = $this->model->find($id);
        if ($employee) {
            $employee->update([
                'unit_id' => $data['after_unit_id'],
                'department_id' => $data['department_id'],
                'shift_group_id' => $data['shift_group_id'],
                'kabag_id' => $data['kabag_id'],
                'supervisor_id' => $data['supervisor_id'],
                'manager_id' => $data['manager_id'],
            ]);
            return $employee;
        }
        return null;
    }

    public function updatePositionId($id, $data)
    {
        $employee = $this->model->find($id);
        if ($employee) {
            $employee->update([
                'position_id' => $data['position_id'],
                'department_id' => $data['department_id'],
                'shift_group_id' => $data['shift_group_id'],
                'kabag_id' => $data['kabag_id'],
                'supervisor_id' => $data['supervisor_id'],
                'manager_id' => $data['manager_id'],
            ]);
            return $employee;
        }
        return null;
    }

    public function employeeWherePin($pin)
    {
        $employee = $this->model
            ->where('pin', $pin)
            ->first('id');
        return $employee ? $employee : $employee = null;
    }

    public function employeeWhereEmployeeNumber($employeeNumber)
    {
        $employee = $this->model
            ->where('employment_number', $employeeNumber)
            ->first(
                [
                    'id',
                    'name',
                    'email',
                    'position_id',
                    'unit_id',
                    'department_id',
                    'employment_number',
                    'user_id',
                    'supervisor_id',
                    'manager_id',
                    'pin',
                    'shift_group_id'
                ]
            );
        return $employee ? $employee : $employee = null;
    }

    public function employeeActive($perPage, $search = null)
    {
        $now = Carbon::now()->toDateString();
        $query = $this->model
            ->where('started_at', '!=', null)
            ->where('employment_number', '!=', null)
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
                'shiftGroup' => function ($query) {
                    $query->select('id', 'name');
                },
                'manager' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'supervisor' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'kabag' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'username', 'firebase_id', 'active')->with([
                        'roles:id,name',
                        'roles.permissions:id,name',
                    ]);
                },
                'contract' => function ($query) {
                    $query->select('id');
                }
            ])
            ->select($this->field);
        $query->where(function ($query) use ($now) {
            $query->where(function ($query) use ($now) {
                $query->where('started_at', '<=', $now)
                    ->where(function ($query) use ($now) {
                        $query->where('resigned_at', '>=', $now);
                    });
            });
        });
        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"]);
        }
        return $query->orderBy('name', 'ASC')->paginate($perPage);
    }

    public function employeeHaveContractDetail()
    {
        $query = $this->model
            ->with([
                'contract' => function ($query) {
                    $query->select('id', 'employee_id', 'transaction_number', 'start_at', 'end_at', 'hour_per_day')->with([
                        'employeeContractDetail:id,employee_contract_id,nominal',
                    ])->latest();
                }
            ])
            ->select($this->field);
        return $query->get();
    }

    public function employeeSubordinate($perPage, $search = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }
        $query = $this->model
            ->where(function ($query) use ($user) {
                $query->where('supervisor_id', $user->employee->id)
                    ->orWhere('manager_id', $user->employee->id)
                    ->orWhere('kabag_id', $user->employee->id);
            })
            ->where('resigned_at', '>=', Carbon::now()->toDateString());
        if ($search !== null) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                    ->orWhere('employment_number', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        return $query->select($this->field)->paginate($perPage);
    }

    public function employeeSubordinateMobile($employeeId)
    {
        $user = Employee::where('id', $employeeId)->first();
        if (!$user) {
            return [];
        }
        $query = $this->model
            ->where(function ($query) use ($user) {
                $query->where('supervisor_id', $user->id)
                    ->orWhere('manager_id', $user->id)
                    ->orWhere('kabag_id', $user->id);
            })
            ->where('resigned_at', '>=', Carbon::now()->toDateString());
        return $query->select($this->field)->get();
    }

    public function employeeNonShift()
    {
        return $this->model->where('resigned_at', '>=', Carbon::now()->toDateString())
            ->where('shift_group_id', '01hfhe3aqcbw9r1fxvr2j2tb75')
            ->get(['id', 'name', 'employment_number', 'shift_group_id']);
    }

    public function employeeResigned($perPage, $search = null)
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
                'shiftGroup' => function ($query) {
                    $query->select('id', 'name');
                },
                'manager' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'supervisor' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'kabag' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'username', 'firebase_id', 'active')->with([
                        'roles:id,name',
                        'roles.permissions:id,name',
                    ]);
                },
                'contract' => function ($query) {
                    $query->select('id', 'employee_id', 'transaction_number', 'start_at', 'end_at')->with([
                        'employeeContractDetail:id,employee_contract_id,nominal',
                    ]);
                }
            ])
            ->select($this->field)
            ->where('resigned_at', '<=', Carbon::now()->toDateString());

        if ($search !== null) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                    ->orWhere('employment_number', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        return $query->orderBy('employment_number', 'ASC')->paginate($perPage);
    }

    public function checkActiveEmployeeMobile($employeeId)
    {
        $employee = $this->model->where('id', $employeeId)->first();
        if ($employee->resigned_at <= Carbon::now()->toDateString()) {
            $user = User::where('id', $employee->user_id)->first();
            $user->active = 0;
            $user->save();
            return [
                'message' => 'Account Non Active!',
                'success' => true,
                'code' => 200,
                'data' => 'Account Non Active!',
            ];
        }
        return [
            'message' => 'Account Active!',
            'success' => true,
            'code' => 200,
            'data' => 'Account Active!',
        ];
    }
}
