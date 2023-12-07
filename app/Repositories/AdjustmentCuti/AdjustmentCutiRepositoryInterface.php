<?php
namespace App\Repositories\AdjustmentCuti;

Interface AdjustmentCutiRepositoryInterface{

    public function index($perPage, $search);
    public function adjustmentCutiEmployee($perPage, $search, $employeeId);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
