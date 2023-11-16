<?php

namespace App\Imports;

use App\Models\{Employee, LogFinger};
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Symfony\Component\Uid\Ulid;

class LogFingerImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $date = $row[4];

        $employeeNumber = $row[0];
        $employee = Employee::where('employment_number', $employeeNumber)->first();

        if (!$employee) {
            return null; // Skip this row
        }

        // Check if the entry already exists in the shift_schedules table
        $existingEntry = LogFinger::where([
            'employee_id' => $employee->id,
            'log_at' => $row[1],
        ])->first();

        // If the entry exists, skip it
        if ($existingEntry) {
            return null; // Skip this row
        }

        $ulid = Ulid::generate(); // Generate a ULID
        return new LogFinger([
            'id' => Str::lower($ulid),
            'log_at' => $row[1],
            'employee_id' => $employee->id,
            'in_out' => $row[1],
            'datetime' => $row[1],
            'manual' => $row[5],
            'code_pin' => $row[6],
            'user_manual_id' => auth()->id(),
        ]);
    }
}
