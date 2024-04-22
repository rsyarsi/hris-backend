<?php

namespace App\Repositories\JobInterviewForm;

use App\Models\{JobInterviewForm};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\JobVacanciesApplied\JobVacanciesAppliedServiceInterface;
use App\Repositories\JobInterviewForm\JobInterviewFormRepositoryInterface;

class JobInterviewFormRepository implements JobInterviewFormRepositoryInterface
{
    private $model;
    private $jobVacancyApplied;

    public function __construct(JobInterviewForm $model, JobVacanciesAppliedServiceInterface $jobVacancyApplied)
    {
        $this->model = $model;
        $this->jobVacancyApplied = $jobVacancyApplied;
    }

    public function index($perPage, $search = null, $period_1 = null, $period_2 = null, $status = null)
    {
        $query = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
                'jobVacancy',
                'jobVacancy.education:id,name',
                'interviewer:id,name,email,employment_number',
                'jobVacanciesApplied',
            ]);

        if ($search !== null) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('candidate_id', $search)
                    ->orWhereHas('candidate', function ($candidateQuery) use ($search) {
                        $candidateQuery->where('first_name', 'ILIKE', "%{$search}%")
                            ->orWhere('middle_name', 'ILIKE', "%{$search}%")
                            ->orWhere('last_name', 'ILIKE', "%{$search}%");
                    })
                    ->orWhereHas('jobVacancy', function ($candidateQuery) use ($search) {
                        $candidateQuery->where('title', 'ILIKE', "%{$search}%")
                            ->orWhere('position', 'ILIKE', "%{$search}%");
                    })
                    ->orWhereHas('interviewer', function ($candidateQuery) use ($search) {
                        $candidateQuery->where('name', 'ILIKE', "%{$search}%")
                            ->orWhere('employment_number', 'ILIKE', "%{$search}%");
                    });
            });
        }
        if ($period_1) {
            $query->whereDate('date', '>=', $period_1);
        }
        if ($period_2) {
            $query->whereDate('date', '<=', $period_2);
        }
        if ($status) {
            $query->whereRaw('LOWER(status) LIKE ?', ["%" . strtolower($status) . "%"]);
        }
        return $query->orderBy('date', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $jobInterviewForm = $this->model->create($data);
            $existingCount = $this->model->where('job_vacancies_applied_id', $jobInterviewForm->job_vacancies_applied_id)->count();
            if ($existingCount === 1) {
                $this->jobVacancyApplied->update($jobInterviewForm->job_vacancies_applied_id, ['status' => 'INTERVIEW']);
            }
            DB::commit();
            return $jobInterviewForm;
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function show($id)
    {
        $jobInterviewForm = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
                'jobVacancy',
                'jobVacancy.education:id,name',
                'interviewer:id,name,email,employment_number',
                'jobVacanciesApplied',
            ])
            ->where('id', $id)
            ->first();
        return $jobInterviewForm ? $jobInterviewForm : $jobInterviewForm = null;
    }

    public function update($id, $data)
    {
        $jobInterviewForm = $this->model->find($id);
        if ($jobInterviewForm) {
            $jobInterviewForm->update($data);
            return $jobInterviewForm;
        }
        return null;
    }

    public function destroy($id)
    {
        $jobInterviewForm = $this->model->find($id);
        if ($jobInterviewForm) {
            $jobInterviewForm->delete();
            return $jobInterviewForm;
        }
        return null;
    }

    public function interviewer($perPage, $search = null, $period_1 = null, $period_2 = null, $status = null)
    {
        $query = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
                'jobVacancy',
                'jobVacancy.education:id,name',
                'interviewer:id,name,email,employment_number',
                'jobVacanciesApplied',
            ])->where('interviewer_id', Auth::user()->employee->id);

        if ($search !== null) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('candidate_id', $search)
                    ->orWhereHas('candidate', function ($candidateQuery) use ($search) {
                        $candidateQuery->where('first_name', 'ILIKE', "%{$search}%")
                            ->orWhere('middle_name', 'ILIKE', "%{$search}%")
                            ->orWhere('last_name', 'ILIKE', "%{$search}%");
                    })
                    ->orWhereHas('jobVacancy', function ($candidateQuery) use ($search) {
                        $candidateQuery->where('title', 'ILIKE', "%{$search}%")
                            ->orWhere('position', 'ILIKE', "%{$search}%");
                    });
            });
        }
        if ($period_1) {
            $query->whereDate('date', '>=', $period_1);
        }
        if ($period_2) {
            $query->whereDate('date', '<=', $period_2);
        }
        if ($status) {
            $query->whereRaw('LOWER(status) LIKE ?', ["%" . strtolower($status) . "%"]);
        }
        return $query->orderBy('date', 'DESC')->paginate($perPage);
    }
}
