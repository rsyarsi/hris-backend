<?php
namespace App\Services\Overtime;

interface OvertimeServiceInterface
{
    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function overtimeEmployee($perPage, $overtimeStatus, $startDate, $endDate);
    public function overtimeEmployeeMobile($employeeId);
    public function overtimeHrdMobile();
    public function overtimeSupervisorOrManager($perPage, $search, $overtimeStatus, $startDate, $endDate);
    public function overtimeStatus($perPage, $search, $overtimeStatus);
    public function updateStatus($id, $newStatusId);
    public function updateStatusMobile($overtimeId, $overtimeStatusId);
    public function overtimeEmployeeToday($employeeId);
    public function overtimeSupervisorOrManagerMobile($employeeId);
}
