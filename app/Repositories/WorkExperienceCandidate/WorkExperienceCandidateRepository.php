<?php

namespace App\Repositories\WorkExperienceCandidate;

use App\Models\WorkExperienceCandidate;
use App\Repositories\WorkExperienceCandidate\WorkExperienceCandidateRepositoryInterface;


class WorkExperienceCandidateRepository implements WorkExperienceCandidateRepositoryInterface
{
    private $model;

    public function __construct(WorkExperienceCandidate $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
            ]);

        if ($search !== null) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('candidate_id', $search)
                    ->orWhere('company', 'ILIKE', "%{$search}%")
                    ->orWhere('position', 'ILIKE', "%{$search}%")
                    ->orWhere('location', 'ILIKE', "%{$search}%")
                    ->orWhereHas('candidate', function ($candidateQuery) use ($search) {
                        $candidateQuery->where('first_name', 'ILIKE', "%{$search}%")
                            ->orWhere('middle_name', 'ILIKE', "%{$search}%")
                            ->orWhere('last_name', 'ILIKE', "%{$search}%");
                    });
            });
        }
        return $query->orderBy('candidate_id', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $workExperienceCandidate = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
            ])
            ->where('id', $id)
            ->first();
        return $workExperienceCandidate ? $workExperienceCandidate : $workExperienceCandidate = null;
    }

    public function update($id, $data)
    {
        $workExperienceCandidate = $this->model->find($id);
        if ($workExperienceCandidate) {
            $workExperienceCandidate->update($data);
            return $workExperienceCandidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $workExperienceCandidate = $this->model->find($id);
        if ($workExperienceCandidate) {
            $workExperienceCandidate->delete();
            return $workExperienceCandidate;
        }
        return null;
    }
}
