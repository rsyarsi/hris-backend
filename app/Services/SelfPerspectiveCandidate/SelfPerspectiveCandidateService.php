<?php

namespace App\Services\SelfPerspectiveCandidate;

use Illuminate\Support\Str;
use App\Services\SelfPerspectiveCandidate\SelfPerspectiveCandidateServiceInterface;
use App\Repositories\SelfPerspectiveCandidate\SelfPerspectiveCandidateRepositoryInterface;

class SelfPerspectiveCandidateService implements SelfPerspectiveCandidateServiceInterface
{
    private $repository;

    public function __construct(SelfPerspectiveCandidateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['self_perspective'] = $this->formatTextTitle($data['self_perspective']);
        $data['strengths'] = $this->formatTextTitle($data['strengths']);
        $data['weaknesses'] = $this->formatTextTitle($data['weaknesses']);
        $data['successes'] = $this->formatTextTitle($data['successes']);
        $data['failures'] = $this->formatTextTitle($data['failures']);
        $data['career_overview'] = $this->formatTextTitle($data['career_overview']);
        $data['future_expectations'] = $this->formatTextTitle($data['future_expectations']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['self_perspective'] = $this->formatTextTitle($data['self_perspective']);
        $data['strengths'] = $this->formatTextTitle($data['strengths']);
        $data['weaknesses'] = $this->formatTextTitle($data['weaknesses']);
        $data['successes'] = $this->formatTextTitle($data['successes']);
        $data['failures'] = $this->formatTextTitle($data['failures']);
        $data['career_overview'] = $this->formatTextTitle($data['career_overview']);
        $data['career_overview'] = $this->formatTextTitle($data['career_overview']);
        $data['future_expectations'] = $this->formatTextTitle($data['future_expectations']);
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function formatTextTitle($data)
    {
        return Str::ucfirst($data);
    }
}
