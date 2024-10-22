<?php

namespace App\Repositories\EmergencyContactCandidate;

use App\Models\{EmergencyContactCandidate};
use App\Repositories\EmergencyContactCandidate\EmergencyContactCandidateRepositoryInterface;


class EmergencyContactCandidateRepository implements EmergencyContactCandidateRepositoryInterface
{
    private $model;

    public function __construct(EmergencyContactCandidate $model)
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
        $emergencyContactcandidate = $this->model
                            ->with([
                                'candidate:id,first_name,middle_name,last_name,email',
                                'relationship:id,name',
                                'sex:id,name',
                            ])
                            ->where('id', $id)
                            ->first();
        return $emergencyContactcandidate ? $emergencyContactcandidate : $emergencyContactcandidate = null;
    }

    public function update($id, $data)
    {
        $emergencyContactcandidate = $this->model->find($id);
        if ($emergencyContactcandidate) {
            $emergencyContactcandidate->update($data);
            return $emergencyContactcandidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $emergencyContactcandidate = $this->model->find($id);
        if ($emergencyContactcandidate) {
            $emergencyContactcandidate->delete();
            return $emergencyContactcandidate;
        }
        return null;
    }
}
