<?php

namespace Database\Seeders;

use App\Models\Relationship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $relationships = [
            ['name' => 'SUAMI', 'active' => 1],
            ['name' => 'ISTRI', 'active' => 1],
            ['name' => 'ANAK', 'active' => 1],
            ['name' => 'ORANG TUA', 'active' => 1],
        ];
        Relationship::insert($relationships);
    }
}
