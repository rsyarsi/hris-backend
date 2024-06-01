<?php

namespace App\Repositories\OrganizationExperienceCandidate;

use App\Models\OrganizationExperienceCandidate;
use App\Repositories\OrganizationExperienceCandidate\OrganizationExperienceCandidateRepositoryInterface;


class OrganizationExperienceCandidateRepository implements OrganizationExperienceCandidateRepositoryInterface
{
    private $model;

    public function __construct(OrganizationExperienceCandidate $model)
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

    public function indexByCandidate($candidateId)
    {
        return $this->model->where('candidate_id', $candidateId)->get();
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $organizationExperienceCandidate = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
            ])
            ->where('id', $id)
            ->first();
        return $organizationExperienceCandidate ? $organizationExperienceCandidate : $organizationExperienceCandidate = null;
    }

    public function update($id, $data)
    {
        $organizationExperienceCandidate = $this->model->find($id);
        if ($organizationExperienceCandidate) {
            $organizationExperienceCandidate->update($data);
            return $organizationExperienceCandidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $organizationExperienceCandidate = $this->model->find($id);
        if ($organizationExperienceCandidate) {
            $organizationExperienceCandidate->delete();
            return $organizationExperienceCandidate;
        }
        return null;
    }
}
