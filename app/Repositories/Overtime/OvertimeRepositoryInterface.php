<?php
namespace App\Repositories\Overtime;

Interface OvertimeRepositoryInterface
{
    public function index($perPage, $search, $period_1, $period_2, $unit);
    public function overtimeEmployeeRekap($perPage, $employeeId, $period_1, $period_2);
    public function overtimeUnitRekap($perPage, $search, $period_1, $period_2, $unit);
    public function overtimedepartmentRekap($perPage, $search, $period_1, $period_2, $department);
    public function store(array $data);
    public function overtimeCreateMobile(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function overtimeEmployee($perPage, $overtimeStatus, $startDate, $endDate);
    public function overtimeEmployeeMobile($employeeId);
    public function overtimeHrdMobile();
    public function overtimeSupervisorOrManager($perPage, $search, $overtimeStatus, $startDate, $endDate);
    public function overtimeStatus($perPage, $search, $period_1, $period_2, $overtimeStatus, $unit);
    public function updateStatus($id, array $data);
    public function updateStatusMobile($overtimeId, $overtimeStatusId);
    public function overtimeEmployeeToday($employeeId);
    public function overtimeSupervisorOrManagerMobile($employeeId);
}
