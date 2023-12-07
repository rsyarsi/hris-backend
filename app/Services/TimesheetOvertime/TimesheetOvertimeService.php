<?php
namespace App\Services\TimesheetOvertime;

use App\Services\TimesheetOvertime\TimesheetOvertimeServiceInterface;
use App\Repositories\TimesheetOvertime\TimesheetOvertimeRepositoryInterface;

class TimesheetOvertimeService implements TimesheetOvertimeServiceInterface
{
    private $repository;

    public function __construct(TimesheetOvertimeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function timesheetOvertimeEmployee($perPage, $search)
    {
        return $this->repository->timesheetOvertimeEmployee($perPage, $search);
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
