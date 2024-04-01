<?php

namespace App\Services\HumanResourcesTest;

use App\Services\HumanResourcesTest\HumanResourcesTestServiceInterface;
use App\Repositories\HumanResourcesTest\HumanResourcesTestRepositoryInterface;
use App\Services\Candidate\CandidateServiceInterface;

class HumanResourcesTestService implements HumanResourcesTestServiceInterface
{
    private $repository;
    private $candidateService;

    public function __construct(HumanResourcesTestRepositoryInterface $repository, CandidateServiceInterface $candidateService)
    {
        $this->repository = $repository;
        $this->candidateService = $candidateService;
    }

    public function index($perPage, $search, $period_1, $period_2)
    {
        return $this->repository->index($perPage, $search, $period_1, $period_2);
    }

    public function store(array $data)
    {
        $candidate = $this->candidateService->show($data['candidate_id']);
        $data['name'] = $candidate->first_name .' '. $candidate->middle_name .' '. $candidate->last_name ?? null;
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $candidate = $this->candidateService->show($data['candidate_id']);
        $data['name'] = $candidate->first_name .' '. $candidate->middle_name .' '. $candidate->last_name ?? null;
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}
