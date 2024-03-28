<?php
namespace App\Services\Candidate;

use Illuminate\Support\Str;
use App\Services\Candidate\CandidateServiceInterface;
use App\Repositories\Candidate\CandidateRepositoryInterface;

class CandidateService implements CandidateServiceInterface
{
    private $repository;

    public function __construct(CandidateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['first_name'] = $this->formatTextTitle($data['first_name']);
        $data['middle_name'] = $data['middle_name'] ?? null;
        $data['last_name'] = $data['last_name'] ?? null;
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['first_name'] = $this->formatTextTitle($data['first_name']);
        $data['middle_name'] = $data['middle_name'] ?? null;
        $data['last_name'] = $data['last_name'] ?? null;
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