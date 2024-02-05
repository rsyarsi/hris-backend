<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, ShouldAutoSize};

class EmployeeExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('excel.employee', [
            'items' => Employee::with([
                'identityType' => function ($query) {
                    $query->select('id', 'name');
                },
                'sex' => function ($query) {
                    $query->select('id', 'name');
                },
                'maritalStatus' => function ($query) {
                    $query->select('id', 'name');
                },
                'religion' => function ($query) {
                    $query->select('id', 'name');
                },
                'province' => function ($query) {
                    $query->select('id', 'code', 'name');
                },
                'city' => function ($query) {
                    $query->select('id', 'code', 'name');
                },
                'district' => function ($query) {
                    $query->select('id', 'code', 'name');
                },
                'village' => function ($query) {
                    $query->select('id', 'code', 'name');
                },
                'currentProvince' => function ($query) {
                    $query->select('id', 'code', 'name');
                },
                'currentCity' => function ($query) {
                    $query->select('id', 'code', 'name');
                },
                'currentDistrict' => function ($query) {
                    $query->select('id', 'code', 'name');
                },
                'currentVillage' => function ($query) {
                    $query->select('id', 'code', 'name');
                },
                'statusEmployment' => function ($query) {
                    $query->select('id', 'name');
                },
                'position' => function ($query) {
                    $query->select('id', 'name');
                },
                'unit' => function ($query) {
                    $query->select('id', 'name');
                },
                'department' => function ($query) {
                    $query->select('id', 'name');
                },
                'shiftGroup' => function ($query) {
                    $query->select('id', 'name');
                },
                'manager' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'supervisor' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'kabag' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'username', 'firebase_id', 'active')->with([
                        'roles:id,name',
                        'roles.permissions:id,name',
                    ]);
                }
            ])
            ->orderBy('name', 'ASC')
            ->get(),
            'i' => 1
        ]);
    }
}
