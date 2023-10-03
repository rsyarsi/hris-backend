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
        for ($i = 1; $i <= 3; $i++) {
            LeaveStatus::create([
                'name' => 'Leave Status ' . $i,
            ]);
        }
    }
}
