<?php

namespace App\Imports;

use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
use App\Models\{Employee, LogFinger};
use Maatwebsite\Excel\Concerns\ToModel;

class LogFingerImport implements ToModel
{
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
        $existingEntry = LogFinger::where([
            'employee_id' => $employee->id,
            'log_at' => $row[1],
            'in_out' => $row[2],
            'datetime' => $row[4],
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
            'in_out' => $row[2],
            'code_sn_finger' => $row[3],
            'datetime' => $row[4],
            'manual' => $row[5],
            'code_pin' => $row[6],
            'user_manual_id' => auth()->id(),
            'input_manual_at' => now(),
        ]);
    }
}
