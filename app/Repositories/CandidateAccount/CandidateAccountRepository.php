<?php

namespace App\Repositories\CandidateAccount;

use App\Models\CandidateAccount;

class CandidateAccountRepository implements CandidateAccountRepositoryInterface
{
    private $model;
    private $field =
    [
        'name',
        'email',
        'username',
        'active',
    ];

    public function __construct(CandidateAccount $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null, $active = null)
    {
        $status = $active == true ? 1 : 0;
        $query = $this->model->with('candidate');
        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                ->orWhere('email', 'LIKE', "%{$search}%");
        }
        if ($active !== null) {
            $query->where('active', $status);
        }
        return $query->orderBy('name', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $candidate = $this->model
            ->with([
                'identityType' => function ($query) {
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
            ])
            ->where('id', $id)
            ->first();
        return $candidate ? $candidate : $candidate = null;
    }

    public function update($id, $data)
    {
        $candidate = $this->model->find($id);
        if ($candidate) {
            $candidate->update($data);
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
