<?php
namespace App\Services\EmployeePositionHistory;

use App\Services\EmployeePositionHistory\EmployeePositionHistoryServiceInterface;
use App\Repositories\EmployeePositionHistory\EmployeePositionHistoryRepositoryInterface;

class EmployeePositionHistoryService implements EmployeePositionHistoryServiceInterface
{
    private $repository;

    public function __construct(EmployeePositionHistoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

}
