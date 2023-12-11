<?php
namespace App\Services\CatatanCuti;

use App\Services\CatatanCuti\CatatanCutiServiceInterface;
use App\Repositories\CatatanCuti\CatatanCutiRepositoryInterface;

class CatatanCutiService implements CatatanCutiServiceInterface
{
    private $repository;

    public function __construct(CatatanCutiRepositoryInterface $repository)
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
}
