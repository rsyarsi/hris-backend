<?php
namespace App\Services\TimesheetOvertime;

interface TimesheetOvertimeServiceInterface
{
    public function index($perPage, $search);
    public function timesheetOvertimeEmployee($perPage, $search, $employeeId);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function executeStoredProcedure($periodeAbsen, $periodePayroll);
}
