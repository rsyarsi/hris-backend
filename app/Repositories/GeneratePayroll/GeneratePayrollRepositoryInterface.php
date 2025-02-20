<?php
namespace App\Repositories\GeneratePayroll;

Interface GeneratePayrollRepositoryInterface
{
    public function index($perPage, $search, $unit, $period);
    public function indexMobile($employeeId);
    public function indexPeriod($period);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function executeStoredProcedure($periodeAbsen, $periodePayroll);
    public function generatePayrollEmployee($perPage, $search, $employeeId);
    public function slipGajiMobile($id);
}
