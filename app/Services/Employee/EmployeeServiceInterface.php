<?php
namespace App\Services\Employee;

interface EmployeeServiceInterface
{
    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function employeeNumberNull($perPage, $search);
    public function employeeEndContract($perPage, $search);
    public function updateEmployeeContract($id, array $data);
    public function updateUserId($id, array $data);
}
