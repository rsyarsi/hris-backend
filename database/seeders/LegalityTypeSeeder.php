<?php

namespace Database\Seeders;

use App\Models\LegalityType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LegalityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 3; $i++) {
            LegalityType::create([
                'name' => 'LEGALITY TYPE ' . $i,
                'active' => rand(0, 1),
                'extended' => true,
            ]);
        }
    }
}
