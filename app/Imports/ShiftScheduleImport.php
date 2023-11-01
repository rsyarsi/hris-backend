<?php

namespace App\Imports;

use App\Models\{Shift, Employee, ShiftSchedule};
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Symfony\Component\Uid\Ulid;

class ShiftScheduleImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $createdUserId = auth()->id();
        $setupUserId = auth()->id();
        $date = $row[3];

        $shiftCode = $row[1];
        $shift = Shift::where('code', $shiftCode)->first();
        
        $employeeNumber = $row[0];
        $employee = Employee::where('employment_number', $employeeNumber)->first();
        
        if (!$employee || !$shift) {
            return null; // Skip this row
        }

        // Check if the entry already exists in the shift_schedules table
        $existingEntry = ShiftSchedule::where([
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
            'date' => $date,
        ])->first();

        // If the entry exists, skip it
        if ($existingEntry) {
            return null; // Skip this row
        }

        $ulid = Ulid::generate(); // Generate a ULID
        return new ShiftSchedule([
            'id' => Str::lower($ulid),
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
            'date' => $date,
            'time_in' => $date . ' ' . $shift->in_time,
            'time_out' => $date . ' ' . $shift->out_time,
            'late_note' => null,
            'shift_exchange_id' => null,
            'user_exchange_id' => null,
            'user_exchange_at' => null,
            'created_user_id' => $createdUserId,
            'updated_user_id' => null, // You may need to set this as per your requirements
            'setup_user_id' => $setupUserId,
            'setup_at' => now(), // You can customize the setup_at value
            'period' => $row[2],
            'leave_note' => null,
            'holiday' => $row[4],
            'night' => $shift->night_shift,
            'national_holiday' => $row[5],
        ]);
    }
}
