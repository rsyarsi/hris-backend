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
        $legalityType = [
            ['name' => 'SIP', 'active' => 1],
            ['name' => 'STR', 'active' => 1],
        ];
        LegalityType::insert($legalityType);
    }
}
