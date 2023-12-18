<?php

namespace App\Imports;

use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
use App\Models\{Shift, Employee, GenerateAbsen, ShiftGroup, ShiftSchedule};
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\{ToModel, WithStartRow};

class ShiftScheduleImport implements ToModel, WithStartRow
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
        $createdUserId = auth()->id();
        $setupUserId = auth()->id();
        $date = $row[3];

        $shiftCode = $row[1];
        // $shift = Shift::where('code', $shiftCode)->first();

        $employeeNumber = $row[0];
        $employee = Employee::where('employment_number', $employeeNumber)->first();

        if (!$employee) {
            return; // reject the request if employee not found
        }

        // shift group
        $shiftGroupId = $employee->shift_group_id;
        $shift = Shift::where('shift_group_id', $shiftGroupId)
                        ->where('code', $shiftCode)
                        ->first();

        $dateCarbon = Carbon::parse($date);
        if ($shift->code == "L" || $shift->code == "LIBUR") {
            $data['period'] = $row[2];
            $data['date'] = $row[3];
            $data['day'] = $dateCarbon->format('l');
            $data['employee_id'] = $employee->id;
            $data['employment_id'] = $employeeNumber;
            $data['shift_id'] = $shift->id;
            $data['date_in_at'] = $date;
            $data['time_in_at'] = '';
            $data['date_out_at'] = $date;
            $data['time_out_at'] = '';
            $data['schedule_date_in_at'] = $date;
            $data['schedule_time_in_at'] = '';
            $data['schedule_date_out_at'] = $date;
            $data['schedule_time_out_at'] = '';
            $data['holiday'] = 1;
            $data['night'] = 0;
            $data['national_holiday'] = 0;
            $data['type'] = '';
            $data['function'] = '';
            $data['note'] = 'LIBUR';
            GenerateAbsen::create($data);
        }

        if (!$employee || !$shift) {
            return null; // Skip this row
        }

        if (!$shift) {
            return;
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

        $timeIn = Carbon::parse($date . ' ' . $shift->in_time);
        $timeOut = $shift->night_shift == 1
                        ? Carbon::parse($date . ' ' . $shift->out_time)->addDay()
                        : Carbon::parse($date . ' ' . $shift->out_time);
        return new ShiftSchedule([
            'id' => Str::lower($ulid),
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
            'date' => $date,
            'time_in' => $timeIn,
            'time_out' => $timeOut,
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
