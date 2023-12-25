<?php
namespace App\Repositories\Employee;

Interface EmployeeRepositoryInterface{

    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function employeeNumberNull($perPage, $search);
    public function employeeEndContract($perPage, $search);
    public function updateEmployeeContract($id, array $data);
    public function updateUserId($id, array $data);
    public function employeeWherePin($pin);
    public function employeeWhereEmployeeNumber($employeeNumber);
    public function employeeActive($perPage, $search);
    public function employeeSubordinate($perPage, $search);
    public function employeeSubordinateMobile($employeeId);
}
