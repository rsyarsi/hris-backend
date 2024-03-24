<?php
namespace App\Services\Candidate;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\Candidate\CandidateServiceInterface;
use App\Repositories\Candidate\CandidateRepositoryInterface;

class CandidateService implements CandidateServiceInterface
{
    private $repository;

    public function __construct(CandidateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $active)
    {
        return $this->repository->index($perPage, $search, $active);
    }

    public function store(array $data)
    {
        $data['name'] = $this->formatTextTitle($data['name']);
        $data['religion_id'] = 1;
        $data['resigned_at'] = '3000-01-01 00:00:00';
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
