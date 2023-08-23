<?php
namespace App\Repositories\Interfaces;

Interface DepartmentRepositoryInterface{
    
    public function allDepartments();
    public function storeDepartment($data);
    public function findDepartment($id);
    public function updateDepartment($data, $id); 
    
}