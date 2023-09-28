<?php
namespace App\Services\EmployeeContractDetail;

use Illuminate\Support\Str;
use App\Services\EmployeeContractDetail\EmployeeContractDetailServiceInterface;
use App\Repositories\EmployeeContractDetail\EmployeeContractDetailRepositoryInterface;

class EmployeeContractDetailService implements EmployeeContractDetailServiceInterface
{
    private $repository;

    public function __construct(EmployeeContractDetailRepositoryInterface $repository)
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
