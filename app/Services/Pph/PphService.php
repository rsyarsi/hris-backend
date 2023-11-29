<?php
namespace App\Services\Pph;

use App\Services\Pph\PphServiceInterface;
use App\Repositories\Pph\PphRepositoryInterface;

class PphService implements PphServiceInterface
{
    private $repository;

    public function __construct(PphRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function pphEmployee($perPage, $search)
    {
        return $this->repository->pphEmployee($perPage, $search);
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
