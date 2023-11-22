<?php
namespace App\Services\LogFingerTemp;

use App\Repositories\Employee\EmployeeRepositoryInterface;
use App\Services\LogFingerTemp\LogFingerTempServiceInterface;
use App\Repositories\LogFingerTemp\LogFingerTempRepositoryInterface;

class LogFingerTempService implements LogFingerTempServiceInterface
{
    private $repository;
    private $employeeRepository;

    public function __construct(LogFingerTempRepositoryInterface $repository, EmployeeRepositoryInterface $employeeRepository)
    {
        $this->repository = $repository;
        $this->employeeRepository = $employeeRepository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $pin = $data['nopin'];
        $employee = $this->employeeRepository->employeeWherePin($pin);
        $data['empployee_id'] = $employee->id;
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

    public function logFingerUser($perPage, $search)
    {
        return $this->repository->logFingerUser($perPage, $search);
    }
}
