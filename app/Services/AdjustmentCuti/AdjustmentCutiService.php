<?php
namespace App\Services\AdjustmentCuti;

use Illuminate\Support\Str;
use App\Services\AdjustmentCuti\AdjustmentCutiServiceInterface;
use App\Repositories\AdjustmentCuti\AdjustmentCutiRepositoryInterface;

class AdjustmentCutiService implements AdjustmentCutiServiceInterface
{
    private $repository;

    public function __construct(AdjustmentCutiRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function adjustmentCutiEmployee($perPage, $search, $employeeId)
    {
        return $this->repository->adjustmentCutiEmployee($perPage, $search, $employeeId);
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
