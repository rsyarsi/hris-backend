<?php
namespace App\Services\ExpertiseCertificationCandidate;

use Illuminate\Support\Str;
use App\Services\ExpertiseCertificationCandidate\ExpertiseCertificationCandidateServiceInterface;
use App\Repositories\ExpertiseCertificationCandidate\ExpertiseCertificationCandidateRepositoryInterface;

class ExpertiseCertificationCandidateService implements ExpertiseCertificationCandidateServiceInterface
{
    private $repository;

    public function __construct(ExpertiseCertificationCandidateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['type_of_expertise'] = $this->formatTextTitle($data['type_of_expertise']);
        $data['qualification_type'] = $this->formatTextTitle($data['qualification_type']);
        $data['given_by'] = $this->formatTextTitle($data['given_by']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['type_of_expertise'] = $this->formatTextTitle($data['type_of_expertise']);
        $data['qualification_type'] = $this->formatTextTitle($data['qualification_type']);
        $data['given_by'] = $this->formatTextTitle($data['given_by']);
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
