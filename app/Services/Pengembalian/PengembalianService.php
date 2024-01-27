<?php
namespace App\Services\Pengembalian;

use App\Services\Pengembalian\PengembalianServiceInterface;
use App\Repositories\Pengembalian\PengembalianRepositoryInterface;

class PengembalianService implements PengembalianServiceInterface
{
    private $repository;

    public function __construct(PengembalianRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function PengembalianEmployee($perPage, $search)
    {
        return $this->repository->PengembalianEmployee($perPage, $search);
    }

    public function store(array $data)
    {
        $data['user_created_id'] = auth()->id();
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
