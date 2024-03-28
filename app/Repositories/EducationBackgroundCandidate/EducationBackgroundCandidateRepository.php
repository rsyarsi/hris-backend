<?php

namespace App\Repositories\EducationBackgroundCandidate;

use App\Models\{EducationBackgroundCandidate};
use App\Repositories\EducationBackgroundCandidate\EducationBackgroundCandidateRepositoryInterface;


class EducationBackgroundCandidateRepository implements EducationBackgroundCandidateRepositoryInterface
{
    private $model;

    public function __construct(EducationBackgroundCandidate $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                        ->with([
                            'candidate:id,first_name,middle_name,last_name,email',
                            'education:id,name',
                        ]);

        if ($search !== null) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('candidate_id', $search)
                    ->orWhere('institution_name', 'ILIKE', "%{$search}%")
                    ->orWhere('major', 'ILIKE', "%{$search}%")
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
        $educationBackgroundcandidate = $this->model
                            ->with([
                                'candidate:id,first_name,middle_name,last_name,email',
                                'education:id,name',
                            ])
                            ->where('id', $id)
                            ->first();
        return $educationBackgroundcandidate ? $educationBackgroundcandidate : $educationBackgroundcandidate = null;
    }

    public function update($id, $data)
    {
        $educationBackgroundcandidate = $this->model->find($id);
        if ($educationBackgroundcandidate) {
            $educationBackgroundcandidate->update($data);
            return $educationBackgroundcandidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $educationBackgroundcandidate = $this->model->find($id);
        if ($educationBackgroundcandidate) {
            $educationBackgroundcandidate->delete();
            return $educationBackgroundcandidate;
        }
        return null;
    }
}
