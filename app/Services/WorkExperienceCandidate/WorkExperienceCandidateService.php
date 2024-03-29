<?php

namespace App\Services\WorkExperienceCandidate;

use Illuminate\Support\Str;
use App\Services\WorkExperienceCandidate\WorkExperienceCandidateServiceInterface;
use App\Repositories\WorkExperienceCandidate\WorkExperienceCandidateRepositoryInterface;

class WorkExperienceCandidateService implements WorkExperienceCandidateServiceInterface
{
    private $repository;

    public function __construct(WorkExperienceCandidateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['company'] = $this->formatTextTitle($data['company']);
        $data['position'] = $this->formatTextTitle($data['position']);
        $data['location'] = $this->formatTextTitle($data['location']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['company'] = $this->formatTextTitle($data['company']);
        $data['position'] = $this->formatTextTitle($data['position']);
        $data['location'] = $this->formatTextTitle($data['location']);
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
