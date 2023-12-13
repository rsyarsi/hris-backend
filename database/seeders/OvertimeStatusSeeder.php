<?php

namespace Database\Seeders;

use App\Models\OvertimeStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OvertimeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OvertimeStatus::create(['name' => 'PENDING']);
        OvertimeStatus::create(['name' => 'APPROVAL KABAG']);
        OvertimeStatus::create(['name' => 'APPROVAL SUPERVISOR']);
        OvertimeStatus::create(['name' => 'APPROVAL MANAGER']);
        OvertimeStatus::create(['name' => 'APPROVAL HRD']);
        OvertimeStatus::create(['name' => 'REJECTED KABAG']);
        OvertimeStatus::create(['name' => 'REJECTED SUPERVISOR']);
        OvertimeStatus::create(['name' => 'REJECTED MANAGER']);
        OvertimeStatus::create(['name' => 'REJECTED HRD']);
        OvertimeStatus::create(['name' => 'CANCEL']);
    }
}
