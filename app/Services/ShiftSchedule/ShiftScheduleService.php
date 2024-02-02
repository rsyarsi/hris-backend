<?php
namespace App\Services\ShiftSchedule;

use Carbon\Carbon;
use App\Services\Shift\ShiftServiceInterface;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use App\Repositories\ShiftSchedule\ShiftScheduleRepositoryInterface;

class ShiftScheduleService implements ShiftScheduleServiceInterface
{
    private $repository;
    private $shiftService;

    public function __construct(
        ShiftScheduleRepositoryInterface $repository,
        ShiftServiceInterface $shiftService,
    )
    {
        $this->repository = $repository;
        $this->shiftService = $shiftService;
    }

    public function index($perPage, $search, $startDate, $endDate)
    {
        return $this->repository->index($perPage, $search, $startDate, $endDate);
    }

    public function shiftScheduleKehadiranEmployee($employeeId, $search, $perPage, $startDate, $endDate, $unit)
    {
        return $this->repository->shiftScheduleKehadiranEmployee($employeeId, $search, $perPage, $startDate, $endDate, $unit);
    }

    public function shiftScheduleKehadiranSubordinate($perPage, $search, $startDate, $endDate, $unit)
    {
        return $this->repository->shiftScheduleKehadiranSubordinate($perPage, $search, $startDate, $endDate, $unit);
    }

    public function shiftScheduleKehadiran($perPage, $startDate, $endDate)
    {
        return $this->repository->shiftScheduleKehadiran($perPage, $startDate, $endDate);
    }

    public function shiftScheduleSubordinate($perPage, $search, $startDate, $endDate)
    {
        return $this->repository->shiftScheduleSubordinate($perPage, $search, $startDate, $endDate);
    }

    public function store(array $data)
    {
        $shiftId = $data['shift_id'] ?? null;
        $shift = $this->shiftService->show($shiftId);
        $data['created_user_id'] = auth()->id();
        $data['setup_user_id'] = auth()->id();
        $data['setup_at'] = now();
        // Calculate time_in and time_out based on night_shift
        $data['time_in'] = $shiftId ? $data['date'] . ' ' . $shift->in_time : null;
        $data['time_out'] = $shiftId ? $data['date'] . ' ' . $shift->out_time : null;
        if ($shiftId && $shift->night_shift) {
            $data['time_out'] = Carbon::parse($data['time_out'])->addDay()->format('Y-m-d H:i:s');
        }
        if ($shift && $shift->libur == 1) {
            $data['holiday'] = 1;
        }
        $data['night'] = $shift->night_shift;
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $shiftSchedule = $this->repository->show($id);
        $shiftId = $data['shift_id'] ?? null;
        $shift = $this->shiftService->show($shiftId);
        // Calculate time_in and time_out based on night_shift
        $data['time_in'] = $shift ? $shiftSchedule->date . ' ' . $shift->in_time : null;
        $data['time_out'] = $shift ? $shiftSchedule->date . ' ' . $shift->out_time : null;
        if ($shift && $shift->night_shift) {
            $data['time_out'] = Carbon::parse($data['time_out'])->addDay()->format('Y-m-d H:i:s');
            $data['holiday'] = 1;
        }
        if ($shift && $shift->libur == "1") {
            $data['holiday'] = 1;
        } else {
            $data['holiday'] = 0;
        }
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function shiftScheduleEmployee($perPage, $startDate, $endDate)
    {
        return $this->repository->shiftScheduleEmployee($perPage, $startDate, $endDate);
    }

    public function storeMultiple(array $data)
    {
        return $this->repository->storeMultiple($data);
    }

    public function updateShiftSchedulesForLeave($employeeId, $fromDate, $toDate, $leaveId, $leaveNote)
    {
        $this->repository->updateShiftSchedulesForLeave($employeeId, $fromDate, $toDate, $leaveId, $leaveNote);
    }

    public function deleteByLeaveId($employeeId, $leaveId)
    {
        $this->repository->deleteByLeaveId($employeeId, $leaveId);
    }

    public function shiftSchedulesExist($employeeId, $fromDate, $toDate)
    {
        $this->repository->shiftSchedulesExist($employeeId, $fromDate, $toDate);
    }

    public function shiftScheduleEmployeeToday($employeeId)
    {
        return $this->repository->shiftScheduleEmployeeToday($employeeId);
    }

    public function shiftScheduleEmployeeMobile($employeeId)
    {
        return $this->repository->shiftScheduleEmployeeMobile($employeeId);
    }

    public function shiftScheduleEmployeeDate($employeeId, $date)
    {
        return $this->repository->shiftScheduleEmployeeDate($employeeId, $date);
    }

    public function updateShiftScheduleExchage($data)
    {
        return $this->repository->updateShiftScheduleExchage($data);
    }

    public function generateShiftScheduleNonShift()
    {
        return $this->repository->generateShiftScheduleNonShift();
    }
}
