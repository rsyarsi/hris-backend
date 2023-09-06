<?php
namespace App\Repositories\Employee;

Interface EmployeeRepositoryInterface{

    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
