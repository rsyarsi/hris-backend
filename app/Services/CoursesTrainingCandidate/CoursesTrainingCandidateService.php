<?php
namespace App\Services\CoursesTrainingCandidate;

use Illuminate\Support\Str;
use App\Services\CoursesTrainingCandidate\CoursesTrainingCandidateServiceInterface;
use App\Repositories\CoursesTrainingCandidate\CoursesTrainingCandidateRepositoryInterface;

class CoursesTrainingCandidateService implements CoursesTrainingCandidateServiceInterface
{
    private $repository;

    public function __construct(CoursesTrainingCandidateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['type_of_training'] = $this->formatTextTitle($data['type_of_training']);
        $data['level'] = $this->formatTextTitle($data['level']);
        $data['organized_by'] = $this->formatTextTitle($data['organized_by']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['type_of_training'] = $this->formatTextTitle($data['type_of_training']);
        $data['level'] = $this->formatTextTitle($data['level']);
        $data['organized_by'] = $this->formatTextTitle($data['organized_by']);
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
