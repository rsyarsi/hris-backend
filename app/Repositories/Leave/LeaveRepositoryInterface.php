<?php
namespace App\Repositories\Leave;

Interface LeaveRepositoryInterface
{
    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function leaveEmployee($perPage, $leaveStatus, $startDate, $endDate);
    public function leaveEmployeeMobile($employeeId);
    public function leaveHrdMobile();
    public function leaveSupervisorOrManager($perPage, $search, $leaveStatus, $startDate, $endDate);
    public function leaveStatus($perPage, $search, $overtimeStatus);
    public function updateStatus($id, array $data);
    public function updateStatusMobile($leaveId, $leaveStatusId);
    public function leaveSisa($employeeId);
    public function leaveSupervisorOrManagerMobile($employeeId);
}
