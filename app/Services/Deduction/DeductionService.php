<?php
namespace App\Services\Deduction;

use App\Services\Employee\EmployeeServiceInterface;
use App\Services\Deduction\DeductionServiceInterface;
use App\Repositories\Deduction\DeductionRepositoryInterface;

class DeductionService implements DeductionServiceInterface
{
    private $repository;
    private $employeeService;

    public function __construct(
        DeductionRepositoryInterface $repository,
        EmployeeServiceInterface $employeeService
    )
    {
        $this->repository = $repository;
        $this->employeeService = $employeeService;
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

    public function storeOuter(array $data)
    {
        $employee = $this->employeeService->employeeWhereEmployeeNumber($data['employee_number']);
        $data['employee_id'] = $employee->id;
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
