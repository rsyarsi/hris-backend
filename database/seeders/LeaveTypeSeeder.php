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
        for ($i = 1; $i <= 3; $i++) {
            LeaveType::create([
                'name' => 'LEAVE TYPE ' . $i,
                'is_salary_deduction' => rand(0, 1),
                'active' => rand(0, 1),
            ]);
        }
    }
}
