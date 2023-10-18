<?php
namespace App\Services\Employee;

use Illuminate\Support\Str;
use App\Services\Employee\EmployeeServiceInterface;
use App\Repositories\Employee\EmployeeRepositoryInterface;

class EmployeeService implements EmployeeServiceInterface
{
    private $repository;

    public function __construct(EmployeeRepositoryInterface $repository)
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

    public function employeeNumberNull($perPage, $search)
    {
        return $this->repository->employeeNumberNull($perPage, $search);
    }

    public function employeeEndContract($perPage, $search)
    {
        return $this->repository->employeeEndContract($perPage, $search);
    }

    public function updateEmployeeContract($id, $data)
    {
        return $this->repository->updateEmployeeContract($id, $data);
    }

    public function updateUserId($id, $data)
    {
        return $this->repository->updateUserId($id, $data);
    }

    public function formatTextTitle($data)
    {
        return Str::title($data);
    }
}
