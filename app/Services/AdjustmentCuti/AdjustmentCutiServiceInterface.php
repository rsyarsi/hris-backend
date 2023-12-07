<?php
namespace App\Services\AdjustmentCuti;

interface AdjustmentCutiServiceInterface
{
    public function index($perPage, $search);
    public function adjustmentCutiEmployee($perPage, $search, $employeeId);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
