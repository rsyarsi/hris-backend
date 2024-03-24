<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobVacancyRequest;
use App\Services\JobVacancy\JobVacancyServiceInterface;

class JobVacancyController extends Controller
{
    use ResponseAPI;

    private $jobVacancyService;

    public function __construct(JobVacancyServiceInterface $jobVacancyService)
    {
        $this->middleware('auth:api');
        $this->jobVacancyService = $jobVacancyService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $status = $request->input('status');
            $jobVacancys = $this->jobVacancyService->index($perPage, $search, $startDate, $endDate, $status);
            return $this->success('Job Vacancy retrieved successfully', $jobVacancys);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(JobVacancyRequest $request)
    {
        try {
            $data = $request->validated();
            $jobVacancy = $this->jobVacancyService->store($data);
            return $this->success('Job Vacancy created successfully', $jobVacancy, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $jobVacancy = $this->jobVacancyService->show($id);
            if (!$jobVacancy) {
                return $this->error('Job Vacancy not found', 404);
            }
            return $this->success('Job Vacancy retrieved successfully', $jobVacancy);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(JobVacancyRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $jobVacancy = $this->jobVacancyService->update($id, $data);
            if (!$jobVacancy) {
                return $this->error('Job Vacancy not found', 404);
            }
            return $this->success('Job Vacancy updated successfully', $jobVacancy, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $jobVacancy = $this->jobVacancyService->destroy($id);
            if (!$jobVacancy) {
                return $this->error('Job Vacancy not found', 404);
            }
            return $this->success('Job Vacancy deleted successfully, id : '.$jobVacancy->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
