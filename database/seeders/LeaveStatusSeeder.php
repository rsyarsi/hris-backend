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
        $leaveStatuses = [
            ['name' => 'PENDING'],
            ['name' => 'APPROVAL KABAG'],
            ['name' => 'APPROVAL SUPERVISOR'],
            ['name' => 'APPROVAL MANAGER'],
            ['name' => 'APPROVAL HRD'],
            ['name' => 'REJECTED KABAG'],
            ['name' => 'REJECTED SUPERVISOR'],
            ['name' => 'REJECTED MANAGER'],
            ['name' => 'REJECTED HRD'],
            ['name' => 'CANCEL'],
        ];
        LeaveStatus::insert($leaveStatuses);
    }
}
