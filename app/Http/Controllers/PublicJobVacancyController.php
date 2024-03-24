<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use App\Traits\ResponseAPI;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PublicJobVacancyController extends Controller
{
    use ResponseAPI;

    public function index(): JsonResponse
    {
        $jobVacancies = DB::table('job_vacancies')
            ->join('meducations', 'job_vacancies.education_id', '=', 'meducations.id')
            ->select(
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

        return $this->success('Job Vacancy retrieved successfully', $jobVacancies);
    }
}
