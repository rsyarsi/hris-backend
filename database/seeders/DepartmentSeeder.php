<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            ['name' => 'DEPARTMEN PELAYANAN MEDIS', 'active' => 1],
            ['name' => 'DEPARTMEN PENUNJANG MEDIS', 'active' => 1],
            ['name' => 'DEPARTMEN UMUM', 'active' => 1],
            ['name' => 'DEPARTMEN ADMINISTRASI', 'active' => 1],
            ['name' => 'DEPARTMEN SDI', 'active' => 1],
            ['name' => 'DEPARTMEN KEUANGAN DAN AKUNTANSI', 'active' => 1],
            ['name' => 'DEPARTMEN DIKLIT', 'active' => 1],
            ['name' => 'BOARD OF DIRECTOR', 'active' => 1],
            ['name' => 'DEPARTMEN KEPERAWATAN', 'active' => 1],
            ['name' => 'DEPARTMEN HUMAS & MARKETING', 'active' => 1],
            ['name' => 'DEPARTMEN PURCHASING', 'active' => 1],
            ['name' => 'DEPARTMEN QMR', 'active' => 1],
            ['name' => 'SPI', 'active' => 1],
            ['name' => 'IPCN', 'active' => 1],
            ['name' => 'TIK', 'active' => 1],
        ];
        Department::insert($departments);
    }
}
