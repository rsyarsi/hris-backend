<?php

namespace App\Imports;

use Illuminate\Support\Str;
use App\Models\{Employee, Pph};
use Symfony\Component\Uid\Ulid;
use Maatwebsite\Excel\Concerns\ToModel;

class PphImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (!isset($row[0])) {
            return null;
        }

        $employeeNumber = $row[0];
        $employee = Employee::where('employment_number', $employeeNumber)->first();
        if (!$employee) {
            return null; // Skip this row
        }

        // Check if the entry already exists in the shift_schedules table
        $existingEntry = Pph::where([
            'employee_id' => $employee->id,
            'nilai' => $row[1],
            'period' => $row[2],
        ])->first();

        // If the entry exists, skip it
        if ($existingEntry) {
            return null; // Skip this row
        }

        $ulid = Ulid::generate(); // Generate a ULID
        return new Pph([
            'id' => Str::lower($ulid),
            'employee_id' => $employee->id,
            'nilai' => $row[1],
            'period' => $row[2],
        ]);
    }
}
