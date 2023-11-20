<?php
namespace App\Services\GenerateAbsen;

use App\Services\GenerateAbsen\GenerateAbsenServiceInterface;
use App\Repositories\GenerateAbsen\GenerateAbsenRepositoryInterface;

class GenerateAbsenService implements GenerateAbsenServiceInterface
{
    private $repository;

    public function __construct(GenerateAbsenRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['user_manual_id'] = auth()->id();
        $data['input_manual_at'] = now();
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
