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
            'datetime' => $row[2],
            'time_in' => $row[5],
            'time_out' => $row[6],
            'tgl_log' => $row[7],
        ])->first();

        // If the entry exists, skip it
        if ($existingEntry) {
            return null; // Skip this row
        }

        $uuid = Str::uuid(); // Generate a UUID
        return new LogFinger([
            'id' => $uuid,
            'employee_id' => $employee->id,
            'code_sn_finger' => $row[1],
            'datetime' => $row[2],
            'manual' => $row[3],
            'code_pin' => $row[4],
            'user_manual_id' => auth()->id(),
            'input_manual_at' => now(),
            'time_in' => $row[5],
            'time_out' => $row[6],
            'tgl_log' => $row[7],
            'absen_type' => $row[8],
        ]);
    }
}
