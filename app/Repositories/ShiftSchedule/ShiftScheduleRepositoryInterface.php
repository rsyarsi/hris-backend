<?php
namespace App\Repositories\ShiftSchedule;

Interface ShiftScheduleRepositoryInterface
{
    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function shiftScheduleEmployee($perPage, $startDate, $endDate);
    public function storeMultiple(array $data);
    public function updateShiftSchedulesForLeave($employeeId, $fromDate, $toDate, $leaveId, $leaveNote);
    public function deleteByLeaveId($employeeId, $leaveId);
}
