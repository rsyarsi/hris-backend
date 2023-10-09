<?php

namespace Database\Seeders;

use App\Models\LeaveStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LeaveStatus::create(['name' => 'PENDING']);
        LeaveStatus::create(['name' => 'APPROVAL SUPERVISOR']);
        LeaveStatus::create(['name' => 'APPROVAL MANAGER']);
        LeaveStatus::create(['name' => 'APPROVAL HRD']);
        LeaveStatus::create(['name' => 'REJECTED SUPERVISOR']);
        LeaveStatus::create(['name' => 'REJECTED MANAGER']);
        LeaveStatus::create(['name' => 'REJECTED HRD']);
        LeaveStatus::create(['name' => 'CANCEL']);
    }
}
