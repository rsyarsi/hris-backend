<?php
namespace App\Services\GeneratePayroll;

interface GeneratePayrollServiceInterface
{
    public function index($perPage, $search, $unit);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function executeStoredProcedure($periodeAbsen, $periodePayroll);
    public function generatePayrollEmployee($perPage, $search, $employeeId);
    public function sendSlipGaji($id);
}
