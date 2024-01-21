<?php
namespace App\Services\ShiftSchedule;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\GenerateAbsen;
use Symfony\Component\Uid\Ulid;
use App\Services\Shift\ShiftServiceInterface;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use App\Repositories\ShiftSchedule\ShiftScheduleRepositoryInterface;

class ShiftScheduleService implements ShiftScheduleServiceInterface
{
    private $repository;
    private $shiftService;
    private $employeeService;

    public function __construct(
        ShiftScheduleRepositoryInterface $repository,
        ShiftServiceInterface $shiftService,
        EmployeeServiceInterface $employeeService,
    )
    {
        $this->repository = $repository;
        $this->shiftService = $shiftService;
        $this->employeeService = $employeeService;
    }

    public function index($perPage, $search, $startDate, $endDate)
    {
        return $this->repository->index($perPage, $search, $startDate, $endDate);
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
        // employee
        $employee = $this->employeeService->show($data['employee_id']);
        $shiftId = $data['shift_id'] ?? null;
        $shift = $this->shiftService->show($shiftId);
        // Parse the start_date and end_date
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $shiftSchedules = [];
        // Loop through the date range
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $ulid = Ulid::generate(); // Generate a ULID
            $timeIn = $shiftId ? $date->format('Y-m-d') . ' ' . $shift->in_time : null;
            // Calculate time_out based on night_shift
            $timeOut = $shiftId ? $date->format('Y-m-d') . ' ' . $shift->out_time : null;
            if ($shiftId && $shift->night_shift) {
                $timeOut = $date->copy()->addDay()->format('Y-m-d') . ' ' . $shift->out_time;
            }

            $existingEntryGenerateAbsen = GenerateAbsen::where([
                'employee_id' => $data['employee_id'],
                'shift_id' => $shift->id,
                'date' => $date,
            ])->first();

            if ($existingEntryGenerateAbsen) {
                return null; // Skip this row
            } else if ($date->isSunday()) { // if sunday
                $data['period'] = $data['period'];
                $data['date'] = $date->format('Y-m-d');
                $data['day'] = $date->format('l');
                $data['employee_id'] = $data['employee_id'];
                $data['employment_id'] = $employee->employment_number;
                $data['shift_id'] = $shift->id;
                $data['date_in_at'] = $date->format('Y-m-d');
                $data['time_in_at'] = '';
                $data['date_out_at'] = $date->format('Y-m-d');
                $data['time_out_at'] = '';
                $data['schedule_date_in_at'] = $date->format('Y-m-d');
                $data['schedule_time_in_at'] = '';
                $data['schedule_date_out_at'] = $date->format('Y-m-d');
                $data['schedule_time_out_at'] = '';
                $data['holiday'] = 1;
                $data['night'] = 0;
                $data['national_holiday'] = 0;
                $data['type'] = '';
                $data['function'] = '';
                $data['note'] = 'LIBUR';
                GenerateAbsen::create($data);
            }

            $shiftScheduleData = [
                'id' => Str::lower($ulid),
                'employee_id' => $data['employee_id'],
                'shift_id' => $shiftId,
                'date' => $date->format('Y-m-d'),
                'time_in' => $timeIn,
                'time_out' => $timeOut,
                'late_note' => null,
                'shift_exchange_id' => null,
                'user_exchange_id' => null,
                'user_exchange_at' => null,
                'created_user_id' => auth()->id(),
                'updated_user_id' => null, // You may need to set this as per your requirements
                'setup_user_id' => auth()->id(),
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
