<?php
namespace App\Services\CatatanCuti;

interface CatatanCutiServiceInterface
{
    public function index($perPage, $search);
    public function catatanCutiEmployee($perPage, $search, $employeeId);
    public function catatanCutiEmployeeLatest($employeeId);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function updateStatus($id, array $data);
    public function destroy($id);
}
