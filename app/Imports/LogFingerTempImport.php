<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\{Employee, LogFingerTemp};

class LogFingerTempImport implements ToModel
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
        $existingEntry = LogFingerTemp::where([
            'employee_id' => $employee->id,
            'date_log' => $row[1],
        ])->first();

        // If the entry exists, skip it
        if ($existingEntry) {
            return null; // Skip this row
        }

        $uuid = Str::uuid(); // Generate a UUID
        $datetime = Carbon::parse($row[1]);
        return new LogFingerTemp([
            'id' => $uuid,
            'date_log' => $datetime->toDateString(),
            'employee_id' => $employee->id,
            'function' => $row[7],
            'snfinger' => $row[2],
            'absen' => $row[1],
            'manual' => $row[5],
            'user_manual' => auth()->id(),
            'manual_date' => $datetime->toDateString(),
            'pin' => $row[6],
        ]);
    }
}
