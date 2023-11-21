<?php
namespace App\Services\ShiftSchedule;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
use App\Repositories\ShiftSchedule\ShiftScheduleRepositoryInterface;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use App\Services\Shift\ShiftServiceInterface;

class ShiftScheduleService implements ShiftScheduleServiceInterface
{
    private $repository;
    private $shiftService;

    public function __construct(ShiftScheduleRepositoryInterface $repository, ShiftServiceInterface $shiftService)
    {
        $this->repository = $repository;
        $this->shiftService = $shiftService;
    }

    public function index($perPage, $search, $startDate, $endDate)
    {
        return $this->repository->index($perPage, $search, $startDate, $endDate);
    }

    public function store(array $data)
    {
        $shiftId = $data['shift_id'] ?? null;
        $shift = $this->shiftService->show($shiftId);
        $data['created_user_id'] = auth()->id();
        $data['setup_user_id'] = auth()->id();
        $data['time_in'] = $data['date'] . ' ' . $shift->in_time;
        $data['time_out'] = $data['date'] . ' ' . $shift->out_time;
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
        $data['time_in'] = $shift ? $shiftSchedule->date . ' ' . $shift->in_time : null;
        $data['time_out'] = $shift ? $shiftSchedule->date . ' ' . $shift->out_time : null;
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
        $shiftId = $data['shift_id'] ?? null;
        $shift = $this->shiftService->show($shiftId);

        $createdUserId = auth()->id();
        $setupUserId = auth()->id();

        // Parse the start_date and end_date
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);

        $shiftSchedules = [];

        // Loop through the date range
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $ulid = Ulid::generate(); // Generate a ULID
            $shiftScheduleData = [
                'id' => Str::lower($ulid),
                'employee_id' => $data['employee_id'],
                'shift_id' => $shiftId,
                'date' => $date->format('Y-m-d'),
                'time_in' => $shiftId ? $date->format('Y-m-d') . ' ' . $shift->in_time : null,
                'time_out' => $shiftId ?  $date->format('Y-m-d') . ' ' . $shift->out_time : null,
                'late_note' => null,
                'shift_exchange_id' => null,
                'user_exchange_id' => null,
                'user_exchange_at' => null,
                'created_user_id' => $createdUserId,
                'updated_user_id' => null, // You may need to set this as per your requirements
                'setup_user_id' => $setupUserId,
                'setup_at' => now(), // You can customize the setup_at value
                'period' => $data['period'],
                'leave_note' => null,
                'holiday' => $data['holiday'],
                'night' => $shiftId ? $shift->night_shift : null,
                'national_holiday' => $data['national_holiday'],
            ];

            $shiftSchedules[] = $shiftScheduleData;
        }
        // Insert the shift schedules into the database
        $this->repository->storeMultiple($shiftSchedules);
        return $shiftSchedules;
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
}
