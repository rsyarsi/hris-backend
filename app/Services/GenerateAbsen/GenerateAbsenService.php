<?php
namespace App\Services\GenerateAbsen;

use App\Services\Employee\EmployeeServiceInterface;
use App\Services\GenerateAbsen\GenerateAbsenServiceInterface;
use App\Repositories\GenerateAbsen\GenerateAbsenRepositoryInterface;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use Carbon\Carbon;

class GenerateAbsenService implements GenerateAbsenServiceInterface
{
    private $repository;
    private $employeeService;
    private $shiftScheduleService;

    public function __construct(GenerateAbsenRepositoryInterface $repository, EmployeeServiceInterface $employeeService, ShiftScheduleServiceInterface $shiftScheduleService)
    {
        $this->repository = $repository;
        $this->employeeService = $employeeService;
        $this->shiftScheduleService = $shiftScheduleService;
    }

    public function index($perPage, $search, $period_1, $period_2, $unit)
    {
        return $this->repository->index($perPage, $search, $period_1, $period_2, $unit);
    }

    public function store(array $data)
    {
        $data['user_manual_id'] = auth()->id();
        $data['input_manual_at'] = now();
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

    public function absenFromMobile(array $data)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }
        $shiftSchedule = $this->shiftScheduleService->shiftScheduleEmployeeToday();
        $currentDate = Carbon::now();
        $absenAt = Carbon::parse($data['absen_at']);
        $timeInSchedule = Carbon::parse($shiftSchedule->time_in);
        $timeOutSchedule = Carbon::parse($shiftSchedule->time_out);
        $data['period'] = $currentDate->format('Y-m');
        $data['date'] = $currentDate->format('Y-m-d');
        $data['day'] = $currentDate->format('l');
        $data['employee_id'] = $user->employee->id;
        $data['employment_id'] = $user->employee->employment_number;
        $data['shift_id'] = $shiftSchedule->shift->id;
        $data['date_in_at'] = $timeInSchedule->format('Y-m-d');
        $data['time_in_at'] = $absenAt->format('H:i:s');
        $data['date_out_at'] = $timeOutSchedule->format('Y-m-d');
        $data['time_out_at'] = $absenAt->format('H:i:s'); // update just employee finished works
        $data['schedule_date_in_at'] = $timeInSchedule->format('Y-m-d');
        $data['schedule_time_in_at'] = $timeInSchedule->format('H:i:s');
        $data['schedule_date_out_at'] = $timeOutSchedule->format('Y-m-d');
        $data['schedule_time_out_at'] = $timeOutSchedule->format('H:i:s');
        $data['holiday'] = $shiftSchedule->holiday;
        $data['night'] = $shiftSchedule->night;
        $data['national_holiday'] = $shiftSchedule->national_holiday;
        $data['note'] = 'WARNING';
        // Calculate lateness
        $telat = null;
        if ($absenAt->greaterThan($timeInSchedule)) {
            $telat = $absenAt->diffInMinutes($timeInSchedule);
        }
        $data['telat'] = $telat; // No lateness
        $data['user_manual_id'] = auth()->id();
        $data['input_manual_at'] = now();
        return $this->repository->absenFromMobile($data);
    }
}
