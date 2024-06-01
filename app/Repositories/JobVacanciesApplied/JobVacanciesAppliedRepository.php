<?php

namespace App\Repositories\JobVacanciesApplied;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\{JobVacanciesApplied};
use App\Services\CopyFile\CopyFileServiceInterface;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\EmployeeFamily\EmployeeFamilyServiceInterface;
use App\Services\EmployeeEducation\EmployeeEducationServiceInterface;
use App\Services\EmployeeExperience\EmployeeExperienceServiceInterface;
use App\Services\EmployeeCertificate\EmployeeCertificateServiceInterface;
use App\Services\EmployeeOrganization\EmployeeOrganizationServiceInterface;
use App\Services\FamilyMemberCandidate\FamilyMemberCandidateServiceInterface;
use App\Repositories\JobVacanciesApplied\JobVacanciesAppliedRepositoryInterface;
use App\Services\WorkExperienceCandidate\WorkExperienceCandidateServiceInterface;
use App\Services\EducationBackgroundCandidate\EducationBackgroundCandidateServiceInterface;
use App\Services\ExpertiseCertificationCandidate\ExpertiseCertificationCandidateServiceInterface;
use App\Services\OrganizationExperienceCandidate\OrganizationExperienceCandidateServiceInterface;

class JobVacanciesAppliedRepository implements JobVacanciesAppliedRepositoryInterface
{
    private $model;
    private $copyFileService;
    private $employeeService;
    private $familyMemberCandidateService;
    private $employeeFamilyService;
    private $educationBackgroundCandidateService;
    private $employeeEducationService;
    private $employeeExperienceService;
    private $workExperienceCandidateService;
    private $employeeOrganizationService;
    private $organizationExperienceCandidateService;
    private $expertiseCertificationCandidateService;
    private $employeeCertificateService;

    public function __construct(JobVacanciesApplied $model)
    {
        $this->model = $model;
    }

    private function getCopyFileService()
    {
        if (!$this->copyFileService) {
            $this->copyFileService = app(CopyFileServiceInterface::class);
        }
        return $this->copyFileService;
    }

    private function getEmployeeService()
    {
        if (!$this->employeeService) {
            $this->employeeService = app(EmployeeServiceInterface::class);
        }
        return $this->employeeService;
    }

    // family start
    private function getFamilyMemberCandidateService()
    {
        if (!$this->familyMemberCandidateService) {
            $this->familyMemberCandidateService = app(FamilyMemberCandidateServiceInterface::class);
        }
        return $this->familyMemberCandidateService;
    }

    private function getEmployeeFamilyService()
    {
        if (!$this->employeeFamilyService) {
            $this->employeeFamilyService = app(EmployeeFamilyServiceInterface::class);
        }
        return $this->employeeFamilyService;
    }
    // family end

    // education start
    private function getEducationBackgroundCandidateService()
    {
        if (!$this->educationBackgroundCandidateService) {
            $this->educationBackgroundCandidateService = app(EducationBackgroundCandidateServiceInterface::class);
        }
        return $this->educationBackgroundCandidateService;
    }

    private function getEmployeeEducationService()
    {
        if (!$this->employeeEducationService) {
            $this->employeeEducationService = app(EmployeeEducationServiceInterface::class);
        }
        return $this->employeeEducationService;
    }
    // education end

    // expericence start
    private function getWorkExperienceCandidateService()
    {
        if (!$this->workExperienceCandidateService) {
            $this->workExperienceCandidateService = app(WorkExperienceCandidateServiceInterface::class);
        }
        return $this->workExperienceCandidateService;
    }

    private function getEmployeeExperienceService()
    {
        if (!$this->employeeExperienceService) {
            $this->employeeExperienceService = app(EmployeeExperienceServiceInterface::class);
        }
        return $this->employeeExperienceService;
    }
    // expericence end

    // organization start
    private function getOrganizationExperienceCandidateService()
    {
        if (!$this->organizationExperienceCandidateService) {
            $this->organizationExperienceCandidateService = app(OrganizationExperienceCandidateServiceInterface::class);
        }
        return $this->organizationExperienceCandidateService;
    }

    private function getEmployeeOrganizationService()
    {
        if (!$this->employeeOrganizationService) {
            $this->employeeOrganizationService = app(EmployeeOrganizationServiceInterface::class);
        }
        return $this->employeeOrganizationService;
    }
    // organization end

    // organization start
    private function getExpertiseCertificationCandidateService()
    {
        if (!$this->expertiseCertificationCandidateService) {
            $this->expertiseCertificationCandidateService = app(ExpertiseCertificationCandidateServiceInterface::class);
        }
        return $this->expertiseCertificationCandidateService;
    }

    private function getEmployeeCertificateService()
    {
        if (!$this->employeeCertificateService) {
            $this->employeeCertificateService = app(EmployeeCertificateServiceInterface::class);
        }
        return $this->employeeCertificateService;
    }
    // organization end


    public function index($perPage, $search = null, $status = null)
    {
        $query = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
                'jobVacancy',
                'jobVacancy.education:id,name',
                'jobInterviewForm',
            ]);

        if ($search !== null) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('candidate_id', $search)
                    ->orWhereHas('candidate', function ($candidateQuery) use ($search) {
                        $candidateQuery->where('first_name', 'ILIKE', "%{$search}%")
                            ->orWhere('middle_name', 'ILIKE', "%{$search}%")
                            ->orWhere('last_name', 'ILIKE', "%{$search}%");
                    })
                    ->orWhereHas('jobVacancy', function ($jobVacancyQuery) use ($search) {
                        $jobVacancyQuery->where('title', 'ILIKE', "%{$search}%")
                            ->orWhere('position', 'ILIKE', "%{$search}%");
                    });
            });
        }
        if ($status) {
            $query->whereRaw('LOWER(status) LIKE ?', ["%" . strtolower($status) . "%"]);
        }
        return $query->orderBy('created_at', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $jobVacanciesApplied = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
                'jobVacancy',
                'jobVacancy.education:id,name',
                'jobInterviewForm',
            ])
            ->where('id', $id)
            ->first();
        return $jobVacanciesApplied ? $jobVacanciesApplied : $jobVacanciesApplied = null;
    }

    public function update($id, $data)
    {
        DB::beginTransaction();
        try {
            $jobVacanciesApplied = $this->model->with('candidate')->find($id);
            $jobVacanciesApplied->update($data);
            $message = 'Data Updated Successfully!';
            $error = false;
            $code = 201;
            $fullName = (string)$jobVacanciesApplied->candidate->first_name . ' ' . $jobVacanciesApplied->candidate->middle_name . ' ' . $jobVacanciesApplied->candidate->last_name;
            if (Str::upper($jobVacanciesApplied->status) == "DITERIMA") { // IF CANDIDATE DITERIMA
                $existEmployee = $this->getEmployeeService()->checkNameEmail($fullName, $jobVacanciesApplied->candidate->email);
                if (!$existEmployee) {
                    $dataEmployee = [
                        'name' => $fullName,
                        'sex_id' => $jobVacanciesApplied->candidate->sex_id,
                        'legal_identity_type_id' => $jobVacanciesApplied->candidate->legal_identity_type_id,
                        'legal_identity_number' => $jobVacanciesApplied->candidate->legal_identity_number,
                        'legal_address' => $jobVacanciesApplied->candidate->legal_address,
                        'current_address' => $jobVacanciesApplied->candidate->current_address,
                        'legal_home_phone_number' => $jobVacanciesApplied->candidate->home_phone_number,
                        'current_home_phone_number' => $jobVacanciesApplied->candidate->home_phone_number,
                        'phone_number' => $jobVacanciesApplied->candidate->phone_number,
                        'email' => $jobVacanciesApplied->candidate->email,
                        'birth_place' => $jobVacanciesApplied->candidate->birth_place,
                        'birth_date' => $jobVacanciesApplied->candidate->birth_date,
                        'marital_status_id' => $jobVacanciesApplied->candidate->marital_status_id,
                        'religion_id' => $jobVacanciesApplied->candidate->religion_id,
                        'tax_identify_number' => $jobVacanciesApplied->candidate->tax_identify_number,
                    ];
                    $employee = $this->getEmployeeService()->store($dataEmployee);
                    // employee family
                    $familyMemberCandidate = $this->getFamilyMemberCandidateService()->indexByCandidate($jobVacanciesApplied->candidate->id);
                    foreach ($familyMemberCandidate as $item) {
                        $dataEmployeeFamily = [
                            'employee_id' => $employee->id,
                            'name' => $item->name,
                            'relationship_id' => $item->relationship_id,
                            'as_emergency' => 0,
                            'is_dead' => 0,
                            'birth_date' => $item->birth_date,
                            'phone' => null,
                            'phone_country' => null,
                            'employer_familiescol' => null,
                            'address' => null,
                            'postal_code' => null,
                            'province_id' => null,
                            'city_id' => null,
                            'district_id' => null,
                            'village_id' => null,
                            'job_id' => null,
                        ];
                        $this->getEmployeeFamilyService()->store($dataEmployeeFamily);
                    }

                    // employee education
                    $educationBackgroundCandidates = $this->getEducationBackgroundCandidateService()->indexByCandidate($jobVacanciesApplied->candidate->id);
                    foreach ($educationBackgroundCandidates as $item) {
                        if (isset($item->file_path) && !is_null($item->file_path)) {
                            $sourcePath = $item->file_path; // Example: hrd/candidate/education_backgrounds/candidate_xxxx.pdf
                            $destinationPath = str_replace('hrd/candidate/education_backgrounds', 'hrd/employee_educations', $sourcePath); // New path
                            // Copy the file
                            $fileCopied = $this->getCopyFileService()->copyFile($sourcePath, $destinationPath);
                            if ($fileCopied) {
                                $dataEmployeeEducation = [
                                    'employee_id' => $employee->id,
                                    'education_id' => $item->education_id,
                                    'institution_name' => $item->institution_name,
                                    'major' => $item->major,
                                    'started_year' => $item->started_year,
                                    'ended_year' => $item->ended_year,
                                    'final_score' => $item->final_score,
                                    'is_passed' => 1,
                                    'file_path' => $destinationPath, // Update path to the new destination
                                    'file_url' => $destinationPath, // Assuming the URL path logic is the same
                                    'file_disk' => $item->file_disk,
                                ];
                                $this->getEmployeeEducationService()->storeFromCandidate($dataEmployeeEducation);
                            } else {
                                // Handle the error if the file copy failed
                                Log::error("Failed to copy file for candidate education background ID: " . $item->id);
                            }
                        } else {
                            // Handle the case where file_path is null
                            Log::warning("File path is not set or is null for candidate education background ID: " . $item->id);
                        }
                    }

                    // employee experience
                    $workExperienceCandidate = $this->getWorkExperienceCandidateService()->indexByCandidate($jobVacanciesApplied->candidate->id);
                    foreach ($workExperienceCandidate as $item) {
                        $responsibility = strlen($item->job_description) > 255 ? substr($item->job_description, 0, 255) : $item->job_description;
                        $dataWorkExperience = [
                            'employee_id' => $employee->id,
                            'company_name' => $item->company,
                            'company_field' => $item->position,
                            'responsibility' => $responsibility,
                            'started_at' => $item->from_date,
                            'ended_at' => $item->to_date,
                            'start_position' => $item->position,
                            'end_position' => $item->position,
                            'stop_reason' => $item->reason_for_resignation,
                            'latest_salary' => $item->take_home_pay,
                        ];
                        $this->getEmployeeExperienceService()->store($dataWorkExperience);
                    }

                    // employee organizations
                    $organizationCandidate = $this->getOrganizationExperienceCandidateService()->indexByCandidate($jobVacanciesApplied->candidate->id);
                    foreach ($organizationCandidate as $item) {
                        $dataWorkExperience = [
                            'employee_id' => $employee->id,
                            'institution_name' => $item->organization_name,
                            'position' => $item->position,
                            'started_year' => $item->year,
                            'ended_year' => null
                        ];
                        $this->getEmployeeOrganizationService()->store($dataWorkExperience);
                    }

                    // employee certificates
                    $certificationCandidate = $this->getExpertiseCertificationCandidateService()->indexByCandidate($jobVacanciesApplied->candidate->id);
                    foreach ($certificationCandidate as $item) {
                        $dataCertificationCandidate = [
                            'employee_id' => $employee->id,
                            'name' => $item->type_of_expertise,
                            'institution_name' => $item->given_by,
                            'started_at' => null,
                            'ended_at' => null,
                            'file_url' => null,
                            'file_path' => null,
                            'file_disk' => null,
                            'verified_at' => null,
                            'verified_user_Id' => null
                        ];
                        $this->getEmployeeCertificateService()->storeFromCandidate($dataCertificationCandidate);
                    }

                    $message = 'Data Updated Successfully & Created data employee!';
                }
            }
            DB::commit();
            return [
                'message' => $message,
                'error' => $error,
                'code' => $code,
                'data' => $jobVacanciesApplied
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'message' => $e->getMessage(),
                'error' => true,
                'code' => 422,
                'data' => null
            ];
        }
    }

    public function destroy($id)
    {
        $jobVacanciesApplied = $this->model->find($id);
        if ($jobVacanciesApplied) {
            $jobVacanciesApplied->delete();
            return $jobVacanciesApplied;
        }
        return null;
    }
}
