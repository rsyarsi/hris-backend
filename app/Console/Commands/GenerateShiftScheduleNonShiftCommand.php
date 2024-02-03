<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Employee;
use Illuminate\Support\Str;
use App\Models\GenerateAbsen;
use App\Models\ShiftSchedule;
use Illuminate\Console\Command;
use Symfony\Component\Uid\Ulid;
use App\Http\Controllers\API\V1\ShiftScheduleController;

class GenerateShiftScheduleNonShiftCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:generate-shift-schedule-non-shift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Shift Schedule Non Shift Every Day at 01:00';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $employees = Employee::where('resigned_at', '>=', Carbon::now())
                            ->where('shift_group_id', '01hfhe3aqcbw9r1fxvr2j2tb75')
                            ->get(['id', 'name', 'employment_number', 'shift_group_id']);
        $shift = Shift::where('shift_group_id', '01hfhe3aqcbw9r1fxvr2j2tb75')
                        ->where('code', 'N')
                        ->first();
        $shiftLibur = Shift::where('shift_group_id', '01hfhe3aqcbw9r1fxvr2j2tb75')
                        ->where('code', 'L')
                        ->first();

        foreach ($employees as $employee) {
            // Get the current month's start and end dates
            $date = Carbon::now();
            // $date = Carbon::parse('2024-01-28');
            // Check if a record already exists for this employee and date
            $existingRecord = ShiftSchedule::where('employee_id', $employee->id)
                                            ->where('date', $date->format('Y-m-d'))
                                            ->first();
            $shiftId = $date->isWeekend() ? $shiftLibur->id : $shift->id;
            $timeIn = $date->isWeekend() ? '00:00:00' : $shift->in_time;
            $timeOut = $date->isWeekend() ? '00:00:00' : $shift->out_time;
            if (!$existingRecord){
                $ulid = Ulid::generate(); // Generate a ULID
                $shiftScheduleData = [
                    'id' => Str::lower($ulid),
                    'employee_id' => $employee->id,
                    'shift_id' => $shiftId,
                    'date' => $date->format('Y-m-d'),
                    'time_in' => $date->format('Y-m-d') . ' ' . $timeIn,
                    'time_out' => $date->format('Y-m-d') . ' ' . $timeOut,
                    'late_note' => null,
                    'shift_exchange_id' => null,
                    'user_exchange_id' => null,
                    'user_exchange_at' => null,
                    'created_user_id' => null,
                    'updated_user_id' => null,
                    'setup_user_id' => null,
                    'setup_at' => now(),
                    'period' => $date->format('Y-m'),
                    'leave_note' => null,
                    'holiday' => $date->isWeekend() ?? 0,
                    'night' => 0,
                    'national_holiday' => 0,
                    'import' => 0,
                    'absen_type' => 'ABSEN',
                ];
                $shiftScheduleCreate = ShiftSchedule::create($shiftScheduleData);

                $existingEntryGenerateAbsen = GenerateAbsen::where([
                    'employee_id' => $employee->id,
                    'shift_id' => $shiftId,
                    'date' => $date,
                ])->first();

                if ($existingEntryGenerateAbsen) {
                    return null; // Skip this row
                } else if ($date->isWeekend()) { // if sunday
                    $data['period'] = $date->format('Y-m');
                    $data['date'] = $date->format('Y-m-d');
                    $data['day'] = $date->format('l');
                    $data['employee_id'] = $employee->id;
                    $data['employment_id'] = $employee->employment_number;
                    $data['shift_id'] = $shiftId;
                    $data['date_in_at'] = $date->format('Y-m-d');
                    $data['time_in_at'] = null;
                    $data['date_out_at'] = $date->format('Y-m-d');
                    $data['time_out_at'] = null;
                    $data['schedule_date_in_at'] = $date->format('Y-m-d');
                    $data['schedule_time_in_at'] = null;
                    $data['schedule_date_out_at'] = $date->format('Y-m-d');
                    $data['schedule_time_out_at'] = null;
                    $data['holiday'] = 1;
                    $data['night'] = 0;
                    $data['national_holiday'] = 0;
                    $data['function'] = null;
                    $data['note'] = 'LIBUR';
                    $data['type'] = 'ABSEN';
                    $data['shift_schedule_id'] = $shiftScheduleCreate->id;
                    GenerateAbsen::create($data);
                }
            }
        }
        return Command::SUCCESS;
    }
}
