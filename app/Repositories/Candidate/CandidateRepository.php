<?php

namespace App\Repositories\Candidate;

use Carbon\Carbon;
use App\Models\{Candidate, User};
use Illuminate\Support\Facades\DB;
use App\Repositories\Candidate\CandidateRepositoryInterface;


class CandidateRepository implements CandidateRepositoryInterface
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
        'manager_id', 'pin', 'shift_group_id', 'kabag_id', 'rekening_number', 'status_candidate', 'bpjs_number',
        'bpjstk_number', 'file_url', 'file_path', 'file_disk',
    ];

    public function __construct(Candidate $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null, $active)
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
                            'contract' => function ($query) {
                                $query->select('id', 'candidate_id', 'transaction_number', 'start_at', 'end_at')->with([
                                    'candidateContractDetail:id,candidate_contract_id,nominal',
                                ]);
                            }
                        ])
                        ->select($this->field);

        if ($active === true) {
            $query->where('resigned_at', '>=', Carbon::now()->toDateString());
        }

        if ($search !== null) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
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

    public function candidateProfileMobile($candidateId)
    {
        $candidate = DB::table('candidates')
                        ->select(
                            'candidates.id as candidate_id',
                            'candidates.employment_number as nik',
                            'candidates.name as candidate_name',
                            'candidates.birth_date as tanggal_lahir',
                            'candidates.started_at as tanggal_masuk',
                            // 'candidates.started_at as lama_kerja',
                            DB::raw('EXTRACT(DAY FROM AGE(current_date, candidates.started_at)) as lama_kerja'),
                            'candidates.file_url as photo',
                            'munits.name as unit_name',
                            'msexs.name as jenis_kelamin'
                        )
                        ->leftJoin('munits', 'candidates.unit_id', '=', 'munits.id')
                        ->leftJoin('msexs', 'candidates.sex_id', '=', 'msexs.id')
                        ->where('candidates.id', $candidateId)
                        ->first();
        if ($candidate) {
            return [
                'message' => 'Candidate Retrieved Successfully!',
                'success' => true,
                'code' => 200,
                'data' => [$candidate]
            ];
        }
        return [
            'message' => 'Candidate Retrieved Successfully!',
            'success' => false,
            'code' => 200,
            'data' => ''
        ];
    }

    public function show($id)
    {
        $candidate = $this->model
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
                            'candidateOrganization' => function ($query) {
                                $query->select('id', 'candidate_id', 'institution_name', 'position', 'started_year', 'ended_year');
                            },
                            'candidateLegality' => function ($query) {
                                $query->select(
                                    'id',
                                    'candidate_id',
                                    'legality_type_id',
                                    'started_at',
                                    'ended_at',
                                    'file_url',
                                    'file_path',
                                    'file_disk',
                                )->with('legalityType:id,name');
                            },
                            'candidateExperience' => function ($query) {
                                $query->select(
                                    'id',
                                    'candidate_id',
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
                            'candidateEducation' => function ($query) {
                                $query->select(
                                    'id',
                                    'candidate_id',
                                    'education_id',
                                    'institution_name',
                                    'major',
                                    'started_year',
                                    'ended_year',
                                    'is_passed',
                                )->with('education:id,name');
                            },
                            'candidateFamily' => function ($query) {
                                $query->select(
                                    'id',
                                    'candidate_id',
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
                            'candidateCertificate' => function ($query) {
                                $query->select(
                                    'id',
                                    'candidate_id',
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
                            'candidateSkill' => function ($query) {
                                $query->select(
                                    'id',
                                    'candidate_id',
                                    'skill_type_id',
                                    'candidate_certificate_id',
                                    'description',
                                    'level',
                                )->with([
                                    'skillType:id,name',
                                    'candidateCertificate:id,name,institution_name,started_at,ended_at,file_url,file_path,file_disk,verified_at,verified_user_Id,is_extended',
                                ]);
                            },
                            'contract' => function ($query) {
                                $query->select(
                                    'id',
                                    'candidate_id',
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
                                    'candidate:id,name',
                                    'shiftGroup:id,name,hour,day,type',
                                    'contractType:id,name,active',
                                    'unit:id,name,active',
                                    'position:id,name,active',
                                    'manager:id,name',
                                    'candidateContractDetail:id,candidate_contract_id,payroll_component_id,nominal,active',
                                    'candidateContractDetail.payrollComponent:id,name,active',
                                ]);
                            }
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $candidate ? $candidate : $candidate = null;
    }

    public function update($id, $data)
    {
        $candidate = $this->model->find($id);
        if ($candidate) {
            $candidate->update($data);
            // if ($candidate->resigned_at , '>=', Carbon::now()->toDateString()) {
            //     User::where('id', $candidate->user_id)->update(['active' => 0]);
            // }
            return $candidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $candidate = $this->model->find($id);
        if ($candidate) {
            $candidate->delete();
            return $candidate;
        }
        return null;
    }
}
