<?php

namespace App\Repositories\ExpertiseCertificationCandidate;

use App\Models\ExpertiseCertificationCandidate;
use App\Repositories\ExpertiseCertificationCandidate\ExpertiseCertificationCandidateRepositoryInterface;


class ExpertiseCertificationCandidateRepository implements ExpertiseCertificationCandidateRepositoryInterface
{
    private $model;

    public function __construct(ExpertiseCertificationCandidate $model)
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
                    ->orWhere('type_of_expertise', 'ILIKE', "%{$search}%")
                    ->orWhere('qualification_type', 'ILIKE', "%{$search}%")
                    ->orWhere('given_by', 'ILIKE', "%{$search}%")
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
        $expertiseCertificationCandidate = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
            ])
            ->where('id', $id)
            ->first();
        return $expertiseCertificationCandidate ? $expertiseCertificationCandidate : $expertiseCertificationCandidate = null;
    }

    public function update($id, $data)
    {
        $expertiseCertificationCandidate = $this->model->find($id);
        if ($expertiseCertificationCandidate) {
            $expertiseCertificationCandidate->update($data);
            return $expertiseCertificationCandidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $expertiseCertificationCandidate = $this->model->find($id);
        if ($expertiseCertificationCandidate) {
            $expertiseCertificationCandidate->delete();
            return $expertiseCertificationCandidate;
        }
        return null;
    }
}
