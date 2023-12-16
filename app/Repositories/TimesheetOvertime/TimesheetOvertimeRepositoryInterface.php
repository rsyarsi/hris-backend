<?php
namespace App\Repositories\TimesheetOvertime;

Interface TimesheetOvertimeRepositoryInterface
{
    public function index($perPage, $search, $period);
    public function timesheetOvertimeEmployee($perPage, $search, $employeeId);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function executeStoredProcedure($periodeAbsenStart, $periodeAbsenEnd);
}
