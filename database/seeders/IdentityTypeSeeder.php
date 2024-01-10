<?php

namespace Database\Seeders;

use App\Models\IdentityType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdentityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $identityType = [
            ['name' => 'KTP', 'active' => 1],
            ['name' => 'SIM', 'active' => 1],
            ['name' => 'KTA', 'active' => 1],
            ['name' => 'LAIN-LAIN', 'active' => 1],
        ];
        IdentityType::insert($identityType);
    }
}
