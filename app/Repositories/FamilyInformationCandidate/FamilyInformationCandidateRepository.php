<?php

namespace App\Repositories\FamilyInformationCandidate;

use App\Models\{FamilyInformationCandidate};
use App\Repositories\FamilyInformationCandidate\FamilyInformationCandidateRepositoryInterface;


class FamilyInformationCandidateRepository implements FamilyInformationCandidateRepositoryInterface
{
    private $model;

    public function __construct(FamilyInformationCandidate $model)
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
        $familyInformationcandidate = $this->model
                            ->with([
                                'candidate:id,first_name,middle_name,last_name,email',
                                'relationship:id,name',
                                'sex:id,name',
                                'education:id,name',
                                'job:id,name',
                            ])
                            ->where('id', $id)
                            ->first();
        return $familyInformationcandidate ? $familyInformationcandidate : $familyInformationcandidate = null;
    }

    public function update($id, $data)
    {
        $familyInformationcandidate = $this->model->find($id);
        if ($familyInformationcandidate) {
            $familyInformationcandidate->update($data);
            return $familyInformationcandidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $familyInformationcandidate = $this->model->find($id);
        if ($familyInformationcandidate) {
            $familyInformationcandidate->delete();
            return $familyInformationcandidate;
        }
        return null;
    }
}
