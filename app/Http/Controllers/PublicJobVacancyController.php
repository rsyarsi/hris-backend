<?php

namespace App\Http\Controllers;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
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
}
