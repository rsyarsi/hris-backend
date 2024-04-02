<?php

namespace App\Repositories\HumanResourcesTest;

use App\Models\HumanResourcesTest;
use App\Repositories\HumanResourcesTest\HumanResourcesTestRepositoryInterface;


class HumanResourcesTestRepository implements HumanResourcesTestRepositoryInterface
{
    private $model;

    public function __construct(HumanResourcesTest $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null, $period_1 = null, $period_2 = null)
    {
        $query = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
                'jobVacancy',
                'jobVacancy.education:id,name',
            ]);

        if ($search !== null) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('candidate_id', $search)
                    ->orWhereHas('candidate', function ($candidateQuery) use ($search) {
                        $candidateQuery->where('first_name', 'ILIKE', "%{$search}%")
                            ->orWhere('middle_name', 'ILIKE', "%{$search}%")
                            ->orWhere('last_name', 'ILIKE', "%{$search}%");
                    })->orWhereHas('jobVacancy', function ($jobVacancyQuery) use ($search) {
                        $jobVacancyQuery->where('title', 'ILIKE', "%{$search}%")
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
        return $query->orderBy('date', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $humanResourcesTest = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
                'jobVacancy',
                'jobVacancy.education:id,name',
            ])
            ->where('id', $id)
            ->first();
        return $humanResourcesTest ? $humanResourcesTest : $humanResourcesTest = null;
    }

    public function update($id, $data)
    {
        $humanResourcesTest = $this->model->find($id);
        if ($humanResourcesTest) {
            $humanResourcesTest->update($data);
            return $humanResourcesTest;
        }
        return null;
    }

    public function destroy($id)
    {
        $humanResourcesTest = $this->model->find($id);
        if ($humanResourcesTest) {
            $humanResourcesTest->delete();
            return $humanResourcesTest;
        }
        return null;
    }
}
