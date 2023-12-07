<?php
namespace App\Services\TimesheetOvertime;

use App\Services\TimesheetOvertime\TimesheetOvertimeServiceInterface;
use App\Repositories\TimesheetOvertime\TimesheetOvertimeRepositoryInterface;
use App\Services\Employee\EmployeeServiceInterface;

class TimesheetOvertimeService implements TimesheetOvertimeServiceInterface
{
    private $repository;
    private $employeeService;

    public function __construct(TimesheetOvertimeRepositoryInterface $repository, EmployeeServiceInterface $employeeService)
    {
        $this->repository = $repository;
        $this->employeeService = $employeeService;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function timesheetOvertimeEmployee($perPage, $search, $employeeId)
    {
        return $this->repository->timesheetOvertimeEmployee($perPage, $search, $employeeId);
    }

    public function store(array $data)
    {
        $employee = $this->employeeService->show($data['employee_id']);
        $data['employee_name'] = $employee->name ?? null;
        $data['unitname'] = $employee->unit->name ?? null;
        $data['positionname'] = $employee->position->name ?? null;
        $data['departmenname'] = $employee->department->name ?? null;
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $employee = $this->employeeService->show($data['employee_id']);
        $data['employee_name'] = $employee->name ?? null;
        $data['unitname'] = $employee->unit->name ?? null;
        $data['positionname'] = $employee->position->name ?? null;
        $data['departmenname'] = $employee->department->name ?? null;
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}
