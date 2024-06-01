<?php

namespace App\Repositories\FamilyMemberCandidate;

use App\Models\{FamilyMemberCandidate};
use App\Repositories\FamilyMemberCandidate\FamilyMemberCandidateRepositoryInterface;


class FamilyMemberCandidateRepository implements FamilyMemberCandidateRepositoryInterface
{
    private $model;

    public function __construct(FamilyMemberCandidate $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
                'relationship:id,name',
                'sex:id,name',
                'education:id,name',
                'job:id,name',
            ]);

        if ($search !== null) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('candidate_id', $search)
                    ->orWhere('name', 'ILIKE', "%{$search}%")
                    ->orWhereHas('candidate', function ($candidateQuery) use ($search) {
                        $candidateQuery->where('first_name', 'ILIKE', "%{$search}%")
                            ->orWhere('middle_name', 'ILIKE', "%{$search}%")
                            ->orWhere('last_name', 'ILIKE', "%{$search}%");
                    });
            });
        }
        return $query->orderBy('name', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $familyMembercandidate = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
                'relationship:id,name',
                'sex:id,name',
                'education:id,name',
                'job:id,name',
            ])
            ->where('id', $id)
            ->first();
        return $familyMembercandidate ? $familyMembercandidate : $familyMembercandidate = null;
    }

    public function update($id, $data)
    {
        $familyMembercandidate = $this->model->find($id);
        if ($familyMembercandidate) {
            $familyMembercandidate->update($data);
            return $familyMembercandidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $familyMembercandidate = $this->model->find($id);
        if ($familyMembercandidate) {
            $familyMembercandidate->delete();
            return $familyMembercandidate;
        }
        return null;
    }

    public function indexByCandidate($candidateId)
    {
        return $this->model->where('candidate_id', $candidateId)->get();
    }
}
