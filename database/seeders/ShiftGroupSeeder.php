<?php

namespace Database\Seeders;

use App\Models\ShiftGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 3; $i++) {
            ShiftGroup::create([
                'name' => 'SHIFT GROUP ' . $i,
                'hour' => rand(0, 50),
                'day' => 22,
                'type' => 'TYPE ' . $i,
            ]);
        }
    }
}
