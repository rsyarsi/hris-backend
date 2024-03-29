<?php

namespace App\Repositories\HospitalConnectionCandidate;

use App\Models\HospitalConnectionCandidate;
use App\Repositories\HospitalConnectionCandidate\HospitalConnectionCandidateRepositoryInterface;


class HospitalConnectionCandidateRepository implements HospitalConnectionCandidateRepositoryInterface
{
    private $model;

    public function __construct(HospitalConnectionCandidate $model)
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
                    ->orWhere('organization_name', 'ILIKE', "%{$search}%")
                    ->orWhere('position', 'ILIKE', "%{$search}%")
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
        $hospitalConnectionCandidate = $this->model
                            ->with([
                                'candidate:id,first_name,middle_name,last_name,email',
                            ])
                            ->where('id', $id)
                            ->first();
        return $hospitalConnectionCandidate ? $hospitalConnectionCandidate : $hospitalConnectionCandidate = null;
    }

    public function update($id, $data)
    {
        $hospitalConnectionCandidate = $this->model->find($id);
        if ($hospitalConnectionCandidate) {
            $hospitalConnectionCandidate->update($data);
            return $hospitalConnectionCandidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $hospitalConnectionCandidate = $this->model->find($id);
        if ($hospitalConnectionCandidate) {
            $hospitalConnectionCandidate->delete();
            return $hospitalConnectionCandidate;
        }
        return null;
    }
}
