<?php

namespace App\Repositories\JobVacancy;

use App\Models\JobVacancy;
use App\Repositories\JobVacancy\JobVacancyRepositoryInterface;

class JobVacancyRepository implements JobVacancyRepositoryInterface
{
    private $model;

    public function __construct(JobVacancy $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null, $startDate = null, $endDate = null, $status = null)
    {
        $query = $this->model
                        ->with([
                            'userCreated:id,name,email',
                            'education:id,name',
                        ])
                        ->select();
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                        ->orWhere('position', 'like', "%$search%");
            });
        }
        if ($status !== null) {
            $query->where('status', $status);
        }
        if ($startDate !== null) {
            $query->where('start_date', '>=', $startDate);
        }
        if ($endDate !== null) {
            $query->where('end_date', '<=', $endDate);
        }
        return $query->orderBy('start_date', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $jobvacancy = $this->model
                            ->with([
                                'userCreated:id,name,email',
                                'education:id,name',
                            ])
                            ->where('id', $id)
                            ->first();
        return $jobvacancy ? $jobvacancy : $jobvacancy = null;
    }

    public function update($id, $data)
    {
        $jobvacancy = $this->model->find($id);
        if ($jobvacancy) {
            $jobvacancy->update($data);
            return $jobvacancy;
        }
        return null;
    }

    public function destroy($id)
    {
        $jobvacancy = $this->model->find($id);
        if ($jobvacancy) {
            $jobvacancy->delete();
            return $jobvacancy;
        }
        return null;
    }
}
