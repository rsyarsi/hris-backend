<?php

namespace Database\Seeders;

use App\Models\ContractType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contractTypes = [
            ['name' => 'PKWT', 'active' => 1],
            ['name' => 'PKWT 2', 'active' => 1],
            ['name' => 'PKWT 3', 'active' => 1],
        ];
        ContractType::insert($contractTypes);
    }
}
