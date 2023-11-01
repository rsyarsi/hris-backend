<?php

namespace Database\Seeders;

use App\Models\PayrollComponent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PayrollComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 3; $i++) {
            PayrollComponent::create([
                'name' => 'PAYROLL COMPONENT ' . $i,
                'active' => rand(0, 1),
            ]);
        }
    }
}
