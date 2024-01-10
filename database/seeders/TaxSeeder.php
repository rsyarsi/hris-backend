<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taxs = [
            ['name' => 'TK0', 'active' => 1],
            ['name' => 'TK1', 'active' => 1],
            ['name' => 'TK2', 'active' => 1],
            ['name' => 'TK3', 'active' => 1],
            ['name' => 'K0', 'active' => 1],
            ['name' => 'K1', 'active' => 1],
            ['name' => 'K2', 'active' => 1],
            ['name' => 'K3', 'active' => 1],
            ['name' => 'K/I/0', 'active' => 1],
            ['name' => 'K/I/1', 'active' => 1],
            ['name' => 'K/I/2', 'active' => 1],
            ['name' => 'K/I/3', 'active' => 1],
        ];
        Tax::insert($taxs);
    }
}
