<?php

namespace App\Repositories\AdditionalInformationCandidate;

use App\Models\AdditionalInformationCandidate;
use App\Repositories\AdditionalInformationCandidate\AdditionalInformationCandidateRepositoryInterface;


class AdditionalInformationCandidateRepository implements AdditionalInformationCandidateRepositoryInterface
{
    private $model;

    public function __construct(AdditionalInformationCandidate $model)
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
        $additionalInformationCandidate = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
            ])
            ->where('id', $id)
            ->first();
        return $additionalInformationCandidate ? $additionalInformationCandidate : $additionalInformationCandidate = null;
    }

    public function update($id, $data)
    {
        $additionalInformationCandidate = $this->model->find($id);
        if ($additionalInformationCandidate) {
            $additionalInformationCandidate->update($data);
            return $additionalInformationCandidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $additionalInformationCandidate = $this->model->find($id);
        if ($additionalInformationCandidate) {
            $additionalInformationCandidate->delete();
            return $additionalInformationCandidate;
        }
        return null;
    }
}
