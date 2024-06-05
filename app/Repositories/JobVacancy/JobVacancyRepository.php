<?php

namespace App\Repositories\JobVacancy;

use Carbon\Carbon;
use App\Models\JobVacancy;
use Illuminate\Support\Facades\DB;
use App\Services\Candidate\CandidateServiceInterface;
use App\Repositories\JobVacancy\JobVacancyRepositoryInterface;
use App\Services\EmergencyContactCandidate\EmergencyContactCandidateServiceInterface;
use App\Services\EducationBackgroundCandidate\EducationBackgroundCandidateServiceInterface;
use App\Services\ForeignLanguageCandidate\ForeignLanguageCandidateServiceInterface;
use App\Services\HospitalConnectionCandidate\HospitalConnectionCandidateServiceInterface;
use App\Services\HumanResourcesTest\HumanResourcesTestServiceInterface;
use App\Services\SelfPerspectiveCandidate\SelfPerspectiveCandidateServiceInterface;

class JobVacancyRepository implements JobVacancyRepositoryInterface
{
    private $model;
    private $candidateService;
    private $emergencyContactCandidateService;
    private $educationBackgroundCandidateService;
    private $hospitalConnectionService;
    private $selfPerspectiveService;
    private $humanResourceTestService;
    private $foreignLanguageService;

    public function __construct(JobVacancy $model)
    {
        $this->model = $model;
    }

    private function getCandidateService()
    {
        if (!$this->candidateService) {
            $this->candidateService = app(CandidateServiceInterface::class);
        }
        return $this->candidateService;
    }

    private function getEmergencyContactCandidateService()
    {
        if (!$this->emergencyContactCandidateService) {
            $this->emergencyContactCandidateService = app(EmergencyContactCandidateServiceInterface::class);
        }
        return $this->emergencyContactCandidateService;
    }

    private function getEducationBackgroundCandidateService()
    {
        if (!$this->educationBackgroundCandidateService) {
            $this->educationBackgroundCandidateService = app(EducationBackgroundCandidateServiceInterface::class);
        }
        return $this->educationBackgroundCandidateService;
    }

    private function getHospitalConnectionService()
    {
        if (!$this->hospitalConnectionService) {
            $this->hospitalConnectionService = app(HospitalConnectionCandidateServiceInterface::class);
        }
        return $this->hospitalConnectionService;
    }

    private function getSelfPerspectiveService()
    {
        if (!$this->selfPerspectiveService) {
            $this->selfPerspectiveService = app(SelfPerspectiveCandidateServiceInterface::class);
        }
        return $this->selfPerspectiveService;
    }

    private function getHumanResourcesTestService()
    {
        if (!$this->humanResourceTestService) {
            $this->humanResourceTestService = app(HumanResourcesTestServiceInterface::class);
        }
        return $this->humanResourceTestService;
    }

    private function getForeignLanguageService()
    {
        if (!$this->foreignLanguageService) {
            $this->foreignLanguageService = app(ForeignLanguageCandidateServiceInterface::class);
        }
        return $this->foreignLanguageService;
    }

    public function index($perPage, $search = null, $startDate = null, $endDate = null, $status = null)
    {
        $query = $this->model
            ->with([
                'userCreated:id,name,email',
                'education:id,name',
            ])
            ->select();
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                    ->orWhere('position', 'like', "%$search%");
            });
        }
        if ($status !== null) {
            $query->where('status', $status);
        }
        if ($startDate !== null) {
            $query->where('start_date', '>=', $startDate);
        }
        if ($endDate !== null) {
            $query->where('end_date', '<=', $endDate);
        }
        return $query->orderBy('start_date', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $jobvacancy = $this->model
            ->with([
                'userCreated:id,name,email',
                'education:id,name',
            ])
            ->where('id', $id)
            ->first();
        return $jobvacancy ? $jobvacancy : $jobvacancy = null;
    }

    public function update($id, $data)
    {
        $jobvacancy = $this->model->find($id);
        if ($jobvacancy) {
            $jobvacancy->update($data);
            return $jobvacancy;
        }
        return null;
    }

    public function destroy($id)
    {
        $jobvacancy = $this->model->find($id);
        if ($jobvacancy) {
            $jobvacancy->delete();
            return $jobvacancy;
        }
        return null;
    }

    public function indexPublic()
    {
        return DB::table('job_vacancies')
            ->join('meducations', 'job_vacancies.education_id', '=', 'meducations.id')
            ->select(
                'job_vacancies.id',
                'job_vacancies.title',
                'job_vacancies.position',
                'job_vacancies.description',
                'job_vacancies.start_date',
                'job_vacancies.end_date',
                'job_vacancies.min_age',
                'job_vacancies.max_age',
                'job_vacancies.experience',
                'job_vacancies.note',
                'meducations.name as education_name'
            )
            ->where('job_vacancies.status', 1)
            ->get();
    }

    public function applyJob(array $data)
    {
        DB::beginTransaction();
        try {
            // Candidate Start
            $dataCandidate = [
                'candidate_account_id' => null,
                'first_name' => $data['first_name_candidate'],
                'middle_name' => $data['middle_name_candidate'],
                'last_name' => $data['last_name_candidate'],
                'sex_id' => $data['sex_id_candidate'],
                'legal_identity_type_id' => 1,
                'legal_identity_number' => $data['legal_identity_number_candidate'],
                'legal_address' => $data['legal_address_candidate'],
                'current_address' => $data['current_address_candidate'],
                'home_phone_number' => $data['phone_number_candidate'],
                'phone_number' => $data['home_phone_number_candidate'],
                'email' => $data['email_candidate'],
                'birth_place' => $data['birth_place_candidate'],
                'birth_date' => $data['birth_date_candidate'],
                'age' => Carbon::parse($data['birth_date_candidate'])->age,
                'marital_status_id' => $data['marital_status_id_candidate'],
                'ethnic_id' => $data['ethnic_id_candidate'],
                'religion_id' => $data['religion_id_candidate'],
                'tax_identify_number' => $data['tax_identify_number_candidate'],
                'weight' => $data['weight_candidate'],
                'height' => $data['height_candidate'],
            ];
            $candidate = $this->getCandidateService()->store($dataCandidate);
            // Upload File Candidate Start
            if ($data['file_cv']) {
                $this->getCandidateService()->uploadCv($candidate->id, $data);
            }
            if ($data['file_photo']) {
                $this->getCandidateService()->uploadPhotoCandidate($candidate->id, $data);
            }
            // Upload File Candidate End
            // Candidate End

            // Emergency Contact Start
            $emergencyContact = [
                'candidate_id' => $candidate->id,
                'relationship_id' => $data['relationship_id_emergency_contact'],
                'name' => $data['name_emergency_contact'],
                'sex_id' => $data['sex_id_emergency_contact'],
                'phone_number' => $data['phone_number_emergency_contact'],
                'address' => $data['address_emergency_contact'],
            ];
            $this->getEmergencyContactCandidateService()->store($emergencyContact);
            // Emergency Contact End

            // Education Background Start
            $dataEducationBackground = [
                'candidate_id' => $candidate->id,
                'education_id' => $data['education_id_education_background'],
                'institution_name' => $data['institution_name_education_background'],
                'major' => $data['major_education_background'],
                'started_year' => $data['started_year_education_background'],
                'ended_year' => $data['ended_year_education_background'],
                'final_score' => $data['final_score_education_background'],
                'file' => $data['file_ijasah_education_background'],
            ];
            $this->getEducationBackgroundCandidateService()->store($dataEducationBackground);
            // Education Background End

            // Hospital Connection Start
            $dataHospitalConnection = [
                'candidate_id' => $candidate->id,
                'relationship_id' => $data['relationship_id_hospital_connection'],
                'name' => $data['name_hospital_connection'],
                'department_id' => $data['department_id_hospital_connection'],
                'position_id' => $data['position_id_hospital_connection']
            ];
            $this->getHospitalConnectionService()->store($dataHospitalConnection);
            // Hospital Connection End

            // Self Perspective Start
            $dataSelfPerspective = [
                'candidate_id' => $candidate->id,
                'self_perspective' => $data['self_perspective'],
                'strengths' => $data['strengths'],
                'weaknesses' => $data['weaknesses'],
                'successes' => $data['successes'],
                'failures' => $data['failures'],
                'career_overview' => $data['career_overview'],
                'future_expectations' => $data['future_expectations'],
            ];
            $this->getSelfPerspectiveService()->store($dataSelfPerspective);
            // Self Perspective End

            // Human Resources Test Start
            $dataHumanResourcesTest = [
                'candidate_id' => $candidate->id,
                'job_vacancy_id' => $data['job_vacancy_id'],
                'date' => now()->format('Y-m-d'),
                'source_of_info' => $data['source_of_info'],
                'motivation' => $data['motivation'],
                'self_assessment' => $data['self_assessment'],
                'desired_position' => $data['desired_position'],
                'coping_with_department_change' => $data['coping_with_department_change'],
                'previous_job_challenges' => $data['previous_job_challenges'],
                'reason_for_resignation' => $data['reason_for_resignation'],
                'conflict_management' => $data['conflict_management'],
                'stress_management' => $data['stress_management'],
                'overtime_availability' => $data['overtime_availability'],
                'handling_complaints' => $data['handling_complaints'],
                'salary_expectation' => $data['salary_expectation'],
                'benefits_facilities' => $data['benefits_facilities'],
            ];
            $this->getHumanResourcesTestService()->store($dataHumanResourcesTest);
            // Human Resources Test End

            // Foreign Language Start
            foreach ($data['language_foreign_language'] as $index => $language) {
                // Create a new array for each language entry
                $languageEntry = [
                    'candidate_id' => $candidate->id,
                    'language' => $language,
                    'speaking_ability_level' => $data['speaking_ability_level_foreign_language'][$index],
                    'writing_ability_level' => $data['writing_ability_level_foreign_language'][$index],
                ];

                // Store the individual language entry
                $this->getForeignLanguageService()->store($languageEntry);
            }
            // Foreign Language End

            DB::commit(); // Commit transaction if successful
            return $candidate;
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on errors
            return $e->getMessage();
        }
    }

    public function maritalStatus()
    {
        return DB::table('mmaritalstatuses')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function religion()
    {
        return DB::table('mreligions')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function ethnic()
    {
        return DB::table('methnics')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function relationship()
    {
        return DB::table('mrelationships')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function education()
    {
        return DB::table('meducations')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function job()
    {
        return DB::table('mjobs')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function position()
    {
        return DB::table('mpositions')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function department()
    {
        return DB::table('mdepartments')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('name', 'ASC')
            ->get();
    }
}
