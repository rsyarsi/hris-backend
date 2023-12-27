<?php
namespace App\Repositories\ShiftSchedule;

Interface ShiftScheduleRepositoryInterface
{
    public function index($perPage, $search, $startDate, $endDate);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function shiftScheduleEmployee($perPage, $startDate, $endDate);
    public function storeMultiple(array $data);
    public function updateShiftSchedulesForLeave($employeeId, $fromDate, $toDate, $leaveId, $leaveNote);
    public function deleteByLeaveId($employeeId, $leaveId);
    public function shiftSchedulesExist($employeeId, $fromDate, $toDate);
    public function shiftScheduleEmployeeToday($employeeId);
    public function shiftScheduleEmployeeMobile($employeeId);
    public function shiftScheduleEmployeeDate($employeeId, $date);
    public function updateShiftScheduleExchage($data);
}
