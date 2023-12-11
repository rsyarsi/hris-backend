<?php
namespace App\Services\GeneratePayroll;

use App\Services\GeneratePayroll\GeneratePayrollServiceInterface;
use App\Repositories\GeneratePayroll\GeneratePayrollRepositoryInterface;

class GeneratePayrollService implements GeneratePayrollServiceInterface
{
    private $repository;

    public function __construct(GeneratePayrollRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $unit)
    {
        return $this->repository->index($perPage, $search, $unit);
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

    public function executeStoredProcedure($periodeAbsen, $periodePayroll)
    {
        return $this->repository->executeStoredProcedure($periodeAbsen, $periodePayroll);
    }

    public function generatePayrollEmployee($perPage, $search, $unit)
    {
        return $this->repository->generatePayrollEmployee($perPage, $search, $unit);
    }
}
