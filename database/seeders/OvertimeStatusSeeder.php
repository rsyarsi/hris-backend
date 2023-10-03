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
        for ($i = 1; $i <= 3; $i++) {
            OvertimeStatus::create([
                'name' => 'Overtime Status ' . $i,
            ]);
        }
    }
}
