<?php

namespace App\Services\HumanResourcesTest;

use App\Services\HumanResourcesTest\HumanResourcesTestServiceInterface;
use App\Repositories\HumanResourcesTest\HumanResourcesTestRepositoryInterface;

class HumanResourcesTestService implements HumanResourcesTestServiceInterface
{
    private $repository;

    public function __construct(HumanResourcesTestRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $period_1, $period_2)
    {
        return $this->repository->index($perPage, $search, $period_1, $period_2);
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
