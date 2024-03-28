<?php
namespace App\Services\FamilyInformationCandidate;

use Illuminate\Support\Str;
use App\Services\FamilyInformationCandidate\FamilyInformationCandidateServiceInterface;
use App\Repositories\FamilyInformationCandidate\FamilyInformationCandidateRepositoryInterface;

class FamilyInformationCandidateService implements FamilyInformationCandidateServiceInterface
{
    private $repository;

    public function __construct(FamilyInformationCandidateRepositoryInterface $repository)
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
