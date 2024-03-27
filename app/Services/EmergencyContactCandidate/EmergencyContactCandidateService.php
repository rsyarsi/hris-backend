<?php
namespace App\Services\EmergencyContactCandidate;

use Illuminate\Support\Str;
use App\Services\EmergencyContactCandidate\EmergencyContactCandidateServiceInterface;
use App\Repositories\EmergencyContactCandidate\EmergencyContactCandidateRepositoryInterface;

class EmergencyContactCandidateService implements EmergencyContactCandidateServiceInterface
{
    private $repository;

    public function __construct(EmergencyContactCandidateRepositoryInterface $repository)
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
