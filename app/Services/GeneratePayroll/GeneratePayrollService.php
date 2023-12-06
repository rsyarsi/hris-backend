<?php
namespace App\Services\GeneratePayroll;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\Overtime\OvertimeServiceInterface;
use App\Services\GeneratePayroll\GeneratePayrollServiceInterface;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use App\Repositories\GeneratePayroll\GeneratePayrollRepositoryInterface;

class GeneratePayrollService implements GeneratePayrollServiceInterface
{
    private $repository;
    private $employeeService;
    private $shiftScheduleService;
    private $overtimeService;

    public function __construct(
        GeneratePayrollRepositoryInterface $repository,
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
}
