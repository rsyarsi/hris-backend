<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobVacanciesAppliedRequest;
use App\Services\JobVacanciesApplied\JobVacanciesAppliedServiceInterface;

class JobVacanciesAppliedController extends Controller
{
    use ResponseAPI;

    private $jobVacanciesAppliedService;

    public function __construct(JobVacanciesAppliedServiceInterface $jobVacanciesAppliedService)
    {
        $this->middleware('api_or_candidate_auth');
        $this->jobVacanciesAppliedService = $jobVacanciesAppliedService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $jobvacanciesapplieds = $this->jobVacanciesAppliedService->index($perPage, $search);
            return $this->success('Job Vacancies Applied retrieved successfully', $jobvacanciesapplieds);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(JobVacanciesAppliedRequest $request)
    {
        $data = $request->validated();
        $jobvacanciesapplied = $this->jobVacanciesAppliedService->store($data);
        return $this->success('Job Vacancies Applied created successfully', $jobvacanciesapplied, 201);
        try {
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $jobvacanciesapplied = $this->jobVacanciesAppliedService->show($id);
            if (!$jobvacanciesapplied) {
                return $this->error('Job Vacancies Applied not found', 404);
            }
            return $this->success('Job Vacancies Applied retrieved successfully', $jobvacanciesapplied);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(JobVacanciesAppliedRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $jobvacanciesapplied = $this->jobVacanciesAppliedService->update($id, $data);
            if (!$jobvacanciesapplied) {
                return $this->error('Job Vacancies Applied not found', 404);
            }
            return $this->success('Job Vacancies Applied updated successfully', $jobvacanciesapplied, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $jobvacanciesapplied = $this->jobVacanciesAppliedService->destroy($id);
            if (!$jobvacanciesapplied) {
                return $this->error('Job Vacancies Applied not found', 404);
            }
            return $this->success('Job Vacancies Applied deleted successfully, id : ' . $jobvacanciesapplied->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
