<?php
namespace App\Repositories\CatatanCuti;

Interface CatatanCutiRepositoryInterface
{
    public function index($perPage, $search);
    public function catatanCutiEmployee($perPage, $search, $employeeId);
    public function catatanCutiEmployeeLatest($employeeId);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function updateStatus($leaveId, array $data);
    public function destroy($id);
    public function historyPemakaianCutiAll($perPage, $search, $unit, $year);
    public function historyPemakaianCutiSubordinate($perPage, $search, $unit, $year);
}
