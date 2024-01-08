<?php
namespace App\Services\Deduction;

use App\Services\Deduction\DeductionServiceInterface;
use App\Repositories\Deduction\DeductionRepositoryInterface;

class DeductionService implements DeductionServiceInterface
{
    private $repository;

    public function __construct(DeductionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $period)
    {
        return $this->repository->index($perPage, $search, $period);
    }

    public function deductionEmployee($perPage, $search)
    {
        return $this->repository->deductionEmployee($perPage, $search);
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
