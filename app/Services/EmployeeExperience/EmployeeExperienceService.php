<?php

namespace App\Services\EmployeeExperience;

use Illuminate\Support\Str;
use App\Services\EmployeeExperience\EmployeeExperienceServiceInterface;
use App\Repositories\EmployeeExperience\EmployeeExperienceRepositoryInterface;

class EmployeeExperienceService implements EmployeeExperienceServiceInterface
{
    private $repository;

    public function __construct(EmployeeExperienceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['company_name'] = $this->formatTextTitle($data['company_name']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['company_name'] = $this->formatTextTitle($data['company_name']);
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
