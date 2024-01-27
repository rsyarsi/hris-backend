<?php

namespace App\Imports;

use Illuminate\Support\Str;
use App\Models\{Employee, Pengembalian};
use Symfony\Component\Uid\Ulid;
use Maatwebsite\Excel\Concerns\{ToModel, WithStartRow};

class PengembalianImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $employeeNumber = $row[0];
        $employee = Employee::where('employment_number', $employeeNumber)->first();
        if (!$employee) {
            return null; // Skip this row
        }

        // Check if the entry already exists in the shift_schedules table
        $existingEntry = Pengembalian::where([
            'employee_id' => $employee->id,
            'amount' => $row[1],
            'payroll_period' => $row[2],
        ])->first();

        // If the entry exists, skip it
        if ($existingEntry) {
            return null; // Skip this row
        }

        $ulid = Ulid::generate(); // Generate a ULID
        return new Pengembalian([
            'id' => Str::lower($ulid),
            'employee_id' => $employee->id,
            'amount' => $row[1],
            'payroll_period' => $row[2],
        ]);
    }
}
