<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            HelperSeeder::class,
            ContractTypeSeeder::class,
            DepartmentSeeder::class,
            EducationSeeder::class,
            IdentityTypeSeeder::class,
            JobSeeder::class,
            LeaveStatusSeeder::class,
            LeaveTypeSeeder::class,
            LegalityTypeSeeder::class,
            MaritalStatusSeeder::class,
            OvertimeStatusSeeder::class,
            PayrollComponentSeeder::class,
            PositionSeeder::class,
            RelationshipSeeder::class,
            ReligionSeeder::class,
            SexSeeder::class,
            ShiftGroupSeeder::class,
            SkillTypeSeeder::class,
            StatusEmploymentSeeder::class,
            TaxSeeder::class,
            UnitSeeder::class,
            UserSeeder::class,
        ]);
    }
}
