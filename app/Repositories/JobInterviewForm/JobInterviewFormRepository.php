<?php

namespace App\Repositories\JobInterviewForm;

use App\Models\{JobInterviewForm};
use Illuminate\Support\Facades\Auth;
use App\Repositories\JobInterviewForm\JobInterviewFormRepositoryInterface;


class JobInterviewFormRepository implements JobInterviewFormRepositoryInterface
{
    private $model;

    public function __construct(JobInterviewForm $model)
    {
        $this->model = $model;
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
        return $this->model->create($data);
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
