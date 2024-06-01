<?php

namespace App\Services\OrganizationExperienceCandidate;

use Illuminate\Support\Str;
use App\Services\OrganizationExperienceCandidate\OrganizationExperienceCandidateServiceInterface;
use App\Repositories\OrganizationExperienceCandidate\OrganizationExperienceCandidateRepositoryInterface;

class OrganizationExperienceCandidateService implements OrganizationExperienceCandidateServiceInterface
{
    private $repository;

    public function __construct(OrganizationExperienceCandidateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function indexByCandidate($candidateId)
    {
        return $this->repository->indexByCandidate($candidateId);
    }

    public function store(array $data)
    {
        $data['organization_name'] = $this->formatTextTitle($data['organization_name']);
        $data['position'] = $this->formatTextTitle($data['position']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['organization_name'] = $this->formatTextTitle($data['organization_name']);
        $data['position'] = $this->formatTextTitle($data['position']);
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function formatTextTitle($data)
    {
        return Str::upper($data);
    }
}
