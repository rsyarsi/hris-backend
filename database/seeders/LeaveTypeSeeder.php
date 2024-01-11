<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leaveTypes = [
            ['name' => 'CUTI TAHUNAN', 'active' => 1],
            ['name' => 'SAKIT', 'active' => 1],
            ['name' => 'IZIN TERLAMBAT', 'active' => 1],
            ['name' => 'IZIN PULANG AWAL', 'active' => 1],
            ['name' => 'IZIN (DINAS LUAR)', 'active' => 1],
            ['name' => 'CUTI MELAHIRKAN', 'active' => 1],
            ['name' => 'CUTI KEGUGURAN', 'active' => 1],
            ['name' => 'CUTI HAJI', 'active' => 1],
            ['name' => 'CUTI UMROH', 'active' => 1],
            ['name' => 'CUTI MENIKAH', 'active' => 1],
            ['name' => 'CUTI ANAK LAHIR', 'active' => 1],
            ['name' => 'CUTI DUKA', 'active' => 1],
            ['name' => 'CUTI KHITANAN ANAK', 'active' => 1],
        ];
        LeaveType::insert($leaveTypes);
    }
}
