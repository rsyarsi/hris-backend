<?php

namespace App\Repositories\JobVacanciesApplied;

use App\Models\{JobVacanciesApplied};
use App\Repositories\JobVacanciesApplied\JobVacanciesAppliedRepositoryInterface;


class JobVacanciesAppliedRepository implements JobVacanciesAppliedRepositoryInterface
{
    private $model;

    public function __construct(JobVacanciesApplied $model)
    {
        $this->model = $model;
    }

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
        $jobVacanciesApplied = $this->model->find($id);
        if ($jobVacanciesApplied) {
            $jobVacanciesApplied->update($data);
            return $jobVacanciesApplied;
        }
        return null;
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
