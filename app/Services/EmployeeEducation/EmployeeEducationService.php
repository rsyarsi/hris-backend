<?php
namespace App\Services\EmployeeEducation;

use Illuminate\Support\Str;
use App\Services\EmployeeEducation\EmployeeEducationServiceInterface;
use App\Repositories\EmployeeEducation\EmployeeEducationRepositoryInterface;

class EmployeeEducationService implements EmployeeEducationServiceInterface
{
    private $repository;

    public function __construct(EmployeeEducationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['institution_name'] = $this->formatTextTitle($data['institution_name']);
        $data['major'] = $this->formatTextTitle($data['major']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['institution_name'] = $this->formatTextTitle($data['institution_name']);
        $data['major'] = $this->formatTextTitle($data['major']);
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
