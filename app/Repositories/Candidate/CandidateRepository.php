<?php

namespace App\Repositories\Candidate;

use App\Models\{Candidate};
use App\Repositories\Candidate\CandidateRepositoryInterface;


class CandidateRepository implements CandidateRepositoryInterface
{
    private $model;

    public function __construct(Candidate $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
            ->with([
                'identityType:id,name',
                'sex:id,name',
                'maritalStatus:id,name',
                'religion:id,name',
                'ethnic:id,name',
                'candidateAccount:id,name,email,username,active',
            ]);

        if ($search !== null) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(first_name) LIKE ?', ["%" . strtolower($search) . "%"])
                    ->orWhere('middle_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        return $query->orderBy('first_name', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $candidate = $this->model
            ->with([
                'candidateAccount:id,name,email,username,active',
                'identityType:id,name',
                'sex:id,name',
                'maritalStatus:id,name',
                'religion:id,name',
                'ethnic:id,name',
                'emergencyContact:id,candidate_id,relationship_id,name,sex_id,address,phone_number',
                'emergencyContact.relationship:id,name',
                'emergencyContact.sex:id,name',
                'familyInformation:id,candidate_id,relationship_id,name,sex_id,birth_place,birth_date,education_id,job_id',
                'familyInformation.relationship:id,name',
                'familyInformation.sex:id,name',
                'familyInformation.education:id,name',
                'familyInformation.job:id,name',
                'educationBackground:id,candidate_id,education_id,institution_name,major,started_year,ended_year,final_score',
                'educationBackground.education:id,name',
                'organizationExperience:id,candidate_id,organization_name,position,year,description',
                'expertiseCertification:id,candidate_id,type_of_expertise,qualification_type,given_by,year,description',
                'coursesTraining:id,candidate_id,type_of_training,level,organized_by,year,description',
                'foreignLanguage:id,candidate_id,language,speaking_ability_level,writing_ability_level',
                'workExperience:id,candidate_id,company,position,location,from_date,to_date,job_description,reason_for_resignation',
                'hospitalConnection:id,candidate_id,relationship_id,name,department_id,position_id',
                'hospitalConnection.relationship:id,name',
                'hospitalConnection.department:id,name',
                'hospitalConnection.position:id,name',
                'selfPerspective:id,candidate_id,self_perspective,strengths,weaknesses,successes,failures,career_overview,future_expectations',
                'additionalInformation:id,candidate_id,physical_condition,severe_diseases,hospitalizations,last_medical_checkup',
                'humanResourcesTest',
                'jobVacanciesApplied',
                'jobInterviewForm',
                'jobInterviewForm.jobVacancy',
                'jobInterviewForm.interviewer:id,name,email,employment_number',
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

    function uploadCv($id, $data)
    {
        $candidate = $this->model->find($id);
        if ($candidate) {
            $candidate->update($data);
            return $candidate;
        }
        return null;
    }

    function uploadPhotoCandidate($id, $data)
    {
        $candidate = $this->model->find($id);
        if ($candidate) {
            $candidate->update($data);
            return $candidate;
        }
        return null;
    }
}
