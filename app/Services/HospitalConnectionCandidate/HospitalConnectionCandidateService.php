<?php

namespace App\Services\HospitalConnectionCandidate;

use Illuminate\Support\Str;
use App\Services\HospitalConnectionCandidate\HospitalConnectionCandidateServiceInterface;
use App\Repositories\HospitalConnectionCandidate\HospitalConnectionCandidateRepositoryInterface;

class HospitalConnectionCandidateService implements HospitalConnectionCandidateServiceInterface
{
    private $repository;

    public function __construct(HospitalConnectionCandidateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['name'] = $this->formatTextTitle($data['name']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['name'] = $this->formatTextTitle($data['name']);
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function formatTextTitle($data)
    {
        return Str::title($data);
    }
}
