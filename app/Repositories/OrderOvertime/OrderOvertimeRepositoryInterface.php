<?php
namespace App\Repositories\OrderOvertime;

Interface OrderOvertimeRepositoryInterface
{
    public function index($perPage, $search, $period_1, $period_2, $unit, $status);
    public function indexSubOrdinate($perPage, $search, $period_1, $period_2, $unit, $status);
    public function indexSubOrdinateMobile($employeeId);
    public function store(array $data);
    public function storeMobile(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
