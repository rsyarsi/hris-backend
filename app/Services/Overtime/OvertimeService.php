<?php
namespace App\Services\Overtime;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Services\Overtime\OvertimeServiceInterface;
use App\Repositories\Overtime\OvertimeRepositoryInterface;

class OvertimeService implements OvertimeServiceInterface
{
    private $repository;

    public function __construct(OvertimeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $period_1, $period_2, $unit)
    {
        return $this->repository->index($perPage, $search, $period_1, $period_2, $unit);
    }

    public function overtimeEmployeeRekap($perPage, $search, $period_1, $period_2, $unit)
    {
        return $this->repository->overtimeEmployeeRekap($perPage, $search, $period_1, $period_2, $unit);
    }

    public function overtimeUnitRekap($perPage, $search, $period_1, $period_2, $unit)
    {
        return $this->repository->overtimeUnitRekap($perPage, $search, $period_1, $period_2, $unit);
    }

    public function overtimedepartmentRekap($perPage, $search, $period_1, $period_2, $unit)
    {
        return $this->repository->overtimedepartmentRekap($perPage, $search, $period_1, $period_2, $unit);
    }

    public function store(array $data)
    {
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $durationInHours = $fromDate->floatDiffInHours($toDate);
        $roundedDuration = round($durationInHours, 2);
        $hours = floor($roundedDuration);
        $minutes = ($roundedDuration - $hours) * 60;
        $data['duration'] = sprintf('%02d.%02d', $hours, $minutes);
        return $this->repository->store($data);
    }

    public function overtimeCreateMobile(array $data)
    {
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $durationInHours = $fromDate->floatDiffInHours($toDate);
        $roundedDuration = round($durationInHours, 2);
        $hours = floor($roundedDuration);
        $minutes = ($roundedDuration - $hours) * 60;
        $data['duration'] = sprintf('%02d.%02d', $hours, $minutes);
        return $this->repository->overtimeCreateMobile($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $durationInHours = $fromDate->floatDiffInHours($toDate);
        $roundedDuration = round($durationInHours, 2);
        $hours = floor($roundedDuration);
        $minutes = ($roundedDuration - $hours) * 60;
        $data['duration'] = sprintf('%02d.%02d', $hours, $minutes);
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function overtimeEmployee($perPage, $overtimeStatus, $startDate, $endDate)
    {
        return $this->repository->overtimeEmployee($perPage, $overtimeStatus, $startDate, $endDate);
    }

    public function overtimeEmployeeMobile($employeeId)
    {
        return $this->repository->overtimeEmployeeMobile($employeeId);
    }

    public function overtimeHrdMobile()
    {
        return $this->repository->overtimeHrdMobile();
    }

    public function overtimeSupervisorOrManager($perPage, $search, $overtimeStatus, $startDate, $endDate)
    {
        $user = auth()->user();
        $allowedRoles = [
            'administrator',
            'hrd',
            'supervisor',
            'manager',
            'kabag',
            'ADMINISTRATOR',
            'HRD',
            'SUPERVISOR',
            'MANAGER',
            'KABAG',
        ];

        if ($user->hasAnyRole($allowedRoles)) {
            return $this->repository->overtimeSupervisorOrManager($perPage, $search, $overtimeStatus, $startDate, $endDate);
        }
        return null;
    }

    public function overtimeStatus($perPage, $search, $period_1, $period_2, $overtimeStatus, $unit)
    {
        $search = Str::upper($search);
        return $this->repository->overtimeStatus($perPage, $search, $period_1, $period_2, $overtimeStatus, $unit);
    }

    public function updateStatus($id, $data)
    {
        return $this->repository->updateStatus($id, $data);
    }

    public function updateStatusMobile($overtimeId, $overtimeStatusId)
    {
        return $this->repository->updateStatusMobile($overtimeId, $overtimeStatusId);
    }

    public function overtimeEmployeeToday($employeeId)
    {
        return $this->repository->overtimeEmployeeToday($employeeId);
    }

    public function overtimeSupervisorOrManagerMobile($employeeId)
    {
        return $this->repository->overtimeSupervisorOrManagerMobile($employeeId);
    }
}
