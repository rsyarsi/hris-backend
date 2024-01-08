<?php
namespace App\Services\Mutation;

use App\Services\Mutation\MutationServiceInterface;
use App\Repositories\Mutation\MutationRepositoryInterface;

class MutationService implements MutationServiceInterface
{
    private $repository;

    public function __construct(MutationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function mutationEmployee($perPage, $search)
    {
        return $this->repository->mutationEmployee($perPage, $search);
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
