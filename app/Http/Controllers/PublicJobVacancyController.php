<?php

namespace App\Http\Controllers;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Requests\ApplyJobRequest;
use App\Services\JobVacancy\JobVacancyServiceInterface;

class PublicJobVacancyController extends Controller
{
    use ResponseAPI;

    private $jobVacancyService;

    public function __construct(JobVacancyServiceInterface $jobVacancyService)
    {
        $this->jobVacancyService = $jobVacancyService;
    }

    public function index(Request $request)
    {
        try {
            $jobVacancys = $this->jobVacancyService->indexPublic();
            return $this->success('Job Vacancy retrieved successfully', $jobVacancys);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function applyJob(ApplyJobRequest $request)
    {
        try {
            $data = $request->validated();
            $jobVacancys = $this->jobVacancyService->applyJob($data);
            return $this->success('Lamaran pekerjaan berhasil dikirim, kami akan melakukan review dan jika anda masuk ke tahap berikutnya, kami akan menghubungi via email atau whatsapp!', $jobVacancys, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function maritalStatus(Request $request)
    {
        try {
            $maritalStatuses = $this->jobVacancyService->maritalStatus();
            return $this->success('Marital Status retrieved successfully', $maritalStatuses);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function relogion(Request $request)
    {
        try {
            $religions = $this->jobVacancyService->religion();
            return $this->success('Religions retrieved successfully', $religions);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function ethnic(Request $request)
    {
        try {
            $ethnics = $this->jobVacancyService->ethnic();
            return $this->success('Ethnics retrieved successfully', $ethnics);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function relationship(Request $request)
    {
        try {
            $relationships = $this->jobVacancyService->relationship();
            return $this->success('Relationship retrieved successfully', $relationships);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function education(Request $request)
    {
        try {
            $educations = $this->jobVacancyService->education();
            return $this->success('Educations retrieved successfully', $educations);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function job(Request $request)
    {
        try {
            $jobs = $this->jobVacancyService->job();
            return $this->success('Jobs retrieved successfully', $jobs);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function position(Request $request)
    {
        try {
            $positions = $this->jobVacancyService->position();
            return $this->success('Positions retrieved successfully', $positions);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function department(Request $request)
    {
        try {
            $departments = $this->jobVacancyService->department();
            return $this->success('Departments retrieved successfully', $departments);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
