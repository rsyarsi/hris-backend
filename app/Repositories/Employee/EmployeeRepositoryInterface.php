<?php

namespace App\Repositories\Employee;

interface EmployeeRepositoryInterface
{
    public function index($perPage, $search, $active);
    public function store(array $data);
    public function show($id);
    public function checkNameEmail($name, $email);
    public function update($id, array $data);
    public function employeeUploadPhoto($id, array $data);
    public function employeeUploadPhotoMobile(array $data);
    public function employeeProfileMobile($employeeId);
    public function destroy($id);
    public function employeeNumberNull($perPage, $search);
    public function employeeEndContract($perPage, $search);
    public function updateEmployeeContract($id, array $data);
    public function updateUserId($id, array $data);
    public function updateUnitId($id, array $data);
    public function updatePositionId($id, array $data);
    public function employeeWherePin($pin);
    public function employeeWhereEmployeeNumber($employeeNumber);
    public function employeeActive($perPage, $search);
    public function employeeSubordinate($perPage, $search);
    public function employeeSubordinateMobile($employeeId);
    public function employeeNonShift();
    public function employeeHaveContractDetail();
    public function employeeResigned($perPage, $search);
    public function checkActiveEmployeeMobile($employeeId);
}
