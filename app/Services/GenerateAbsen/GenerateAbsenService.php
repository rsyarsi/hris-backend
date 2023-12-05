<?php
namespace App\Services\GenerateAbsen;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\Overtime\OvertimeServiceInterface;
use App\Services\GenerateAbsen\GenerateAbsenServiceInterface;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use App\Repositories\GenerateAbsen\GenerateAbsenRepositoryInterface;

class GenerateAbsenService implements GenerateAbsenServiceInterface
{
    private $repository;
    private $employeeService;
    private $shiftScheduleService;
    private $overtimeService;

    public function __construct(
        GenerateAbsenRepositoryInterface $repository,
        EmployeeServiceInterface $employeeService,
        ShiftScheduleServiceInterface $shiftScheduleService,
        OvertimeServiceInterface $overtimeService
    )
    {
        $this->repository = $repository;
        $this->employeeService = $employeeService;
        $this->shiftScheduleService = $shiftScheduleService;
        $this->overtimeService = $overtimeService;
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
        $type = Str::upper($data['Type']); // ABSEN / SPL(SURAT PERINTAH LEMBUR)
        $function = Str::upper($data['Function']); // IN / OUT
        $jam = Carbon::parse($data['Jam']);

        $currentDate = Carbon::parse($data['Tanggal']);
        $dateJamMasuk = $data['Tanggal'].' '.$data['Jam'];
        // $ipAddress = $data['Ip_address'];

        $employmentId = $data['Employment_id']; // nip karyawan
        $employee = $this->employeeService->employeeWhereEmployeeNumber($employmentId);
        if (!$employee) {
            return [];
        }
        $shiftScheduleId = $data['Id_schedule']; // nip karyawan
        $shiftSchedule = $this->shiftScheduleService->show($shiftScheduleId);
        // return $shiftSchedule;

        $timeInScheduleCarbon = Carbon::parse($shiftSchedule->time_in);

        // Calculate the start of the allowed range (1 hour before $timeInSchedule)
        $allowedStartTime = $timeInScheduleCarbon->copy()->subHour();

        // Calculate the end of the allowed range (scheduled time)
        $allowedEndTime = $timeInScheduleCarbon->copy();

        if ($function == 'IN') {
            $result = ($jam->gte($allowedStartTime) && $jam->lte($allowedEndTime) || $jam->gt($allowedEndTime)) ? 'yes' : 'no';
            if ($result === 'no') {
                return [
                    'message' => 'Absen Hanya boleh 1 jam sebelum jadwal!',
                    'data' => []
                ];
            }
        }

        $timeInSchedule = Carbon::parse($shiftSchedule->time_in);
        $timeOutSchedule = Carbon::parse($shiftSchedule->time_out);
        $data['period'] = $currentDate->format('Y-m');
        $data['date'] = $currentDate->format('Y-m-d');
        $data['day'] = $currentDate->format('l');
        $data['employee_id'] = $employee->id;
        $data['employment_id'] = $employee->employment_number;
        $data['shift_id'] = $shiftSchedule->shift->id;

        $data['date_in_at'] = $timeInSchedule->format('Y-m-d');
        $data['time_in_at'] = $jam->format('H:i:s');

        $data['date_out_at'] = $timeOutSchedule->format('Y-m-d');
        $data['time_out_at'] = $jam->format('H:i:s');

        $data['schedule_date_in_at'] = $timeInSchedule->format('Y-m-d');
        $data['schedule_time_in_at'] = $timeInSchedule->format('H:i:s');
        $data['schedule_date_out_at'] = $timeOutSchedule->format('Y-m-d');
        $data['schedule_time_out_at'] = $timeOutSchedule->format('H:i:s');
        $data['holiday'] = $shiftSchedule->holiday;
        $data['night'] = $shiftSchedule->night;
        $data['national_holiday'] = $shiftSchedule->national_holiday;
        $data['note'] = 'WARNING';
        $data['type'] = $type;
        $data['function'] = $function;
        // Calculate lateness
        $telat = null;
        if (Carbon::parse($dateJamMasuk)->greaterThan($timeInSchedule)) {
            $telat = Carbon::parse($dateJamMasuk)->diffInMinutes($timeInSchedule);
        }
        $data['telat'] = $telat;

        // OVERTIME
        if ($type == 'SPL') {
            $overtimeId = $data['Overtime_id'] ?? null;
            $overtime = $this->overtimeService->show($overtimeId);
            $data['overtime_at'] = Carbon::parse($overtime->from_date)->toDateString() ?? null;
            $data['from_date_overtime'] = $overtime->from_date ?? null;
            $data['to_date_overtime'] = $overtime->to_date ?? null;
            $data['duration_overtime'] = $overtime->duration ?? null;
        }
        return $this->repository->absenFromMobile($data);
    }
}
