<?php

namespace App\Repositories\SelfPerspectiveCandidate;

use App\Models\SelfPerspectiveCandidate;
use App\Repositories\SelfPerspectiveCandidate\SelfPerspectiveCandidateRepositoryInterface;


class SelfPerspectiveCandidateRepository implements SelfPerspectiveCandidateRepositoryInterface
{
    private $model;

    public function __construct(SelfPerspectiveCandidate $model)
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
        $selfPerspectiveCandidate = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
            ])
            ->where('id', $id)
            ->first();
        return $selfPerspectiveCandidate ? $selfPerspectiveCandidate : $selfPerspectiveCandidate = null;
    }

    public function update($id, $data)
    {
        $selfPerspectiveCandidate = $this->model->find($id);
        if ($selfPerspectiveCandidate) {
            $selfPerspectiveCandidate->update($data);
            return $selfPerspectiveCandidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $selfPerspectiveCandidate = $this->model->find($id);
        if ($selfPerspectiveCandidate) {
            $selfPerspectiveCandidate->delete();
            return $selfPerspectiveCandidate;
        }
        return null;
    }
}
