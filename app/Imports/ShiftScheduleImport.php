<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
use App\Models\{Shift, Employee, GenerateAbsen, ShiftSchedule};
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithStartRow, WithValidation};

class ShiftScheduleImport implements ToModel, WithStartRow, WithValidation
{
    use Importable;
    protected $importedData = [];
    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '0' => 'required|exists:employees,employment_number',
            '1' => 'required|exists:shifts,code',
            '2' => 'required|date_format:Y-m',
            '3' => 'required|date_format:Y-m-d',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '0.exists' => 'Karyawan tidak ditemukan pada row :attribute.',
            '1.exists' => 'Kode Shift tidak ditemukan pada row :attribute.',
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $date = $row[3];
        $shiftCode = $row[1];
        $employeeNumber = $row[0];
        $employee = Employee::where('employment_number', $employeeNumber)->first();
        if (!$employee) {
            return null; // reject the request if employee not found
        }
        // shift group
        $shiftGroupId = $employee->shift_group_id;
        if (!$shiftGroupId) {
            return null; // reject the request if shiftGroupId = null
        }
        $shift = Shift::where('shift_group_id', $shiftGroupId)
                        ->where('code', Str::upper($shiftCode))
                        ->first();
        if (!$shift) {
            return null;
        }
        $dateCarbon = Carbon::parse($date);
        if (!$employee || !$shift) {
            return null; // Skip this row
        }
        // Check if the entry already exists in the shift_schedules table
        $existingEntry = ShiftSchedule::where([
            'employee_id' => $employee->id,
            'date' => $date,
        ])->first();

        $ulid = Ulid::generate(); // Generate a ULID
        $timeIn = Carbon::parse($date . ' ' . $shift->in_time);
        $timeOut = $shift->night_shift == 1
                        ? Carbon::parse($date . ' ' . $shift->out_time)->addDay()
                        : Carbon::parse($date . ' ' . $shift->out_time);
         // If the entry exists, skip it
        if ($existingEntry) {
            return null; // Skip this row
        } else {
            $shiftSchedule = ShiftSchedule::create([
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
                'created_user_id' => auth()->id(),
                'updated_user_id' => null, // You may need to set this as per your requirements
                'setup_user_id' => auth()->id(),
                'setup_at' => now(), // You can customize the setup_at value
                'period' => $row[2],
                'leave_note' => null,
                'holiday' => $shift->libur,
                'night' => $shift->night_shift,
                'national_holiday' => $row[5],
                'import' => 1,
                'absen_type' => 'ABSEN',
            ]);
        }

        // save data to generate_absen
        $existingEntryGenerateAbsen = GenerateAbsen::where([
            'employee_id' => $employee->id,
            'date' => $date,
        ])->first();

        // If the entry exists, skip it
        if ($existingEntryGenerateAbsen) {
            return null; // Skip this row
        } else if ($shift->code == "L" || $shift->code == "LIBUR") {
            $data['period'] = $row[2];
            $data['date'] = $row[3];
            $data['day'] = $dateCarbon->format('l');
            $data['employee_id'] = $employee->id;
            $data['employment_id'] = $employeeNumber;
            $data['shift_id'] = $shift->id;
            $data['date_in_at'] = $date;
            $data['time_in_at'] = null;
            $data['date_out_at'] = $date;
            $data['time_out_at'] = null;
            $data['schedule_date_in_at'] = $date;
            $data['schedule_time_in_at'] = null;
            $data['schedule_date_out_at'] = $date;
            $data['schedule_time_out_at'] = null;
            $data['holiday'] = 1;
            $data['night'] = 0;
            $data['national_holiday'] = 0;
            $data['type'] = null;
            $data['function'] = null;
            $data['note'] = 'LIBUR';
            $data['type'] = 'ABSEN';
            $data['shift_schedule_id'] = $shiftSchedule->id;
            GenerateAbsen::create($data);
        }
        $this->importedData[] = $shiftSchedule;
        return $shiftSchedule;
    }

    public function getImportedData()
    {
        return $this->importedData;
    }
}
