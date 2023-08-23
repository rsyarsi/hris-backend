<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\mdepartment;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;
 

class DepartmentRepository implements DepartmentRepositoryInterface
{
    
    
    public function allDepartments()
    {
        return mdepartment::all();
    }

    public function storeDepartment($data)
    {
        $department = new mdepartment;
        $department->name = $data->name;
        $department->active = $data->active;
        return $department->save(); 
    }

    public function findDepartment($id)
    {
        return mdepartment::find($id);
    }

    public function updateDepartment($data, $id)
    {
        $department = mdepartment::where('id', $id)->first();
        $department->name = $data['name'];
        $department->active = $data['active'];
        return $department->save();
    }
}