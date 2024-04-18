<?php

namespace App\Services\LogGenerateAbsen;

use App\Services\LogGenerateAbsen\LogGenerateAbsenServiceInterface;
use App\Repositories\LogGenerateAbsen\LogGenerateAbsenRepositoryInterface;

class LogGenerateAbsenService implements LogGenerateAbsenServiceInterface
{
    private $repository;

    public function __construct(
        LogGenerateAbsenRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $period_1, $period_2, $unit)
    {
        return $this->repository->index($perPage, $search, $period_1, $period_2, $unit);
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

    public function findDate($employeeId, $date)
    {
        return $this->repository->findDate($employeeId, $date);
    }
}
