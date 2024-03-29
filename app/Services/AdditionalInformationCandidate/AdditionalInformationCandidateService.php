<?php

namespace App\Services\AdditionalInformationCandidate;

use Illuminate\Support\Str;
use App\Services\AdditionalInformationCandidate\AdditionalInformationCandidateServiceInterface;
use App\Repositories\AdditionalInformationCandidate\AdditionalInformationCandidateRepositoryInterface;

class AdditionalInformationCandidateService implements AdditionalInformationCandidateServiceInterface
{
    private $repository;

    public function __construct(AdditionalInformationCandidateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}
