<?php
namespace App\Repositories\Deduction;

Interface DeductionRepositoryInterface{

    public function index($perPage, $search);
    public function deductionEmployee($perPage, $search, $employeeId);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
