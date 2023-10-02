<?php
namespace App\Services\ShiftSchedule;

use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use App\Repositories\ShiftSchedule\ShiftScheduleRepositoryInterface;
use App\Services\Leave\LeaveService;

class ShiftScheduleService implements ShiftScheduleServiceInterface
{
    private $repository;
    private $leaveService;

    public function __construct(ShiftScheduleRepositoryInterface $repository, LeaveService $leaveService)
    {
        $this->repository = $repository;
        $this->leaveService = $leaveService;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $id = $data['shift_exchange_id'];
        $leave = $this->leaveService->show($id);
        $data['late_note'] = $leave->leaveType->name;
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
