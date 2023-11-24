<?php
namespace App\Repositories\Overtime;

Interface OvertimeRepositoryInterface{

    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function overtimeEmployee($perPage, $overtimeStatus, $startDate, $endDate);
    public function overtimeSupervisorOrManager($perPage, $overtimeStatus, $startDate, $endDate);
    public function overtimeStatus($perPage, $search, $overtimeStatus);
    public function updateStatus($id, array $data);
    public function overtimeEmployeeToday();
}
