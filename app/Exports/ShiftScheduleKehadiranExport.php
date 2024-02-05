<?php

namespace App\Exports;

// use App\Models\ShiftSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, ShouldAutoSize};

class ShiftScheduleKehadiranExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $period1 = request()->input('period_1');
        $period2 = request()->input('period_2');
        $overtimes = DB::table('overtimes')
                        ->select([
                            'shift_schedules.id as shift_schedule_id',
                            'employees.id as employee_id',
                            'employees.name as employee_name',
                            'employees.employment_number as employment_number',
                            DB::raw("'' as shift_code"),
                            DB::raw("'' as shift_name"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.from_date, 'HH24:MI:SS'), '') as in_time"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.to_date, 'HH24:MI:SS'), '') as out_time"),
                            DB::raw("COALESCE(TO_CHAR(shift_schedules.date, 'YYYY-MM-DD'), '') as shift_schedule_date"),
                            DB::raw("COALESCE(TO_CHAR(shift_schedules.date, 'Day'), '') as shift_schedule_day_name"),
                            DB::raw("COALESCE(TO_CHAR(shift_schedules.time_in, 'YYYY-MM-DD HH24:MI:SS'), '') as shift_schedule_time_in"),
                            DB::raw("COALESCE(TO_CHAR(shift_schedules.time_out, 'YYYY-MM-DD HH24:MI:SS'), '') as shift_schedule_time_out"),
                            DB::raw("COALESCE('SPL', '') as schedule_type"),
                            DB::raw("COALESCE(generate_absen.id::text, '') as generate_absen_id"),
                            DB::raw("COALESCE(generate_absen.period, '') as generate_absen_period"),
                            DB::raw("COALESCE(TO_CHAR(generate_absen.date, 'YYYY-MM-DD'), '') as generate_absen_date"),
                            DB::raw("COALESCE(generate_absen.type, '') as generate_absen_type"),
                            DB::raw("COALESCE(generate_absen.time_in_at, '') as generate_absen_time_in_at"),
                            DB::raw("COALESCE(generate_absen.time_out_at, '') as generate_absen_time_out_at"),
                            DB::raw("COALESCE(generate_absen.telat::text, '') as generate_absen_telat"),
                            DB::raw("COALESCE(generate_absen.pa::text, '') as generate_absen_pa"),
                            DB::raw("COALESCE(generate_absen.note::text, '') as generate_absen_note"),
                            DB::raw("'' as leave_from_date"),
                            DB::raw("'' as leave_to_date"),
                            DB::raw("'' as leave_duration"),
                            DB::raw("'' as leave_note"),
                            DB::raw("'' as leave_type_name"),
                            DB::raw("'' as leave_status_name"),
                            DB::raw("COALESCE(overtimes.id, '') as overtime_id"),
                            DB::raw("COALESCE(overtimes.task, '') as overtime_task"),
                            DB::raw("COALESCE(overtimes.note, '') as overtime_note"),
                            DB::raw("'' as unit_name"),
                        ])
                        ->leftJoin('employees', 'overtimes.employee_id', '=', 'employees.id')
                        ->leftJoin('generate_absen', function ($join) {
                            $join->on('overtimes.employee_id', '=', 'generate_absen.employee_id')
                                ->where(DB::raw("CAST(overtimes.from_date AS DATE)"), '=', DB::raw("CAST(generate_absen.date_in_at AS DATE)"))
                                ->where('generate_absen.type', 'SPL');
                        })
                        ->leftJoin('shift_schedules', function ($join) {
                            $join->on('overtimes.employee_id', '=', 'shift_schedules.employee_id')
                                ->where(DB::raw("CAST(overtimes.from_date AS DATE)"), '=', DB::raw("CAST(shift_schedules.date AS DATE)"));
                        })
                        ->whereNotIn('overtimes.overtime_status_id', [6,7,8,9,10])
                        ->whereBetween(DB::raw("CAST(overtimes.from_date AS DATE)"), [$period1, $period2]);
        $items = DB::table('shift_schedules')
                    ->select([
                        'shift_schedules.id as shift_schedule_id',
                        'employees.id as employee_id',
                        'employees.name as employee_name',
                        'employees.employment_number as employment_number',
                        DB::raw("COALESCE(shifts.code, '') as shift_code"),
                        DB::raw("COALESCE(shifts.name, '') as shift_name"),
                        DB::raw("COALESCE(shifts.in_time, '') as in_time"),
                        DB::raw("COALESCE(shifts.out_time, '') as out_time"),
                        DB::raw("COALESCE(TO_CHAR(shift_schedules.date, 'YYYY-MM-DD'), '') as shift_schedule_date"),
                        DB::raw("COALESCE(TO_CHAR(shift_schedules.date, 'Day'), '') as shift_schedule_day_name"),
                        DB::raw("COALESCE(TO_CHAR(shift_schedules.time_in, 'YYYY-MM-DD HH24:MI:SS'), '') as shift_schedule_time_in"),
                        DB::raw("COALESCE(TO_CHAR(shift_schedules.time_out, 'YYYY-MM-DD HH24:MI:SS'), '') as shift_schedule_time_out"),
                        DB::raw("'ABSEN' AS schedule_type"),
                        DB::raw("COALESCE(generate_absen.id::text, '') as generate_absen_id"),
                        DB::raw("COALESCE(generate_absen.period, '') as generate_absen_period"),
                        DB::raw("COALESCE(TO_CHAR(generate_absen.date, 'YYYY-MM-DD'), '') as generate_absen_date"),
                        DB::raw("COALESCE(generate_absen.type, '') as generate_absen_type"),
                        DB::raw("COALESCE(generate_absen.time_in_at, '') as generate_absen_time_in_at"),
                        DB::raw("COALESCE(generate_absen.time_out_at, '') as generate_absen_time_out_at"),
                        DB::raw("COALESCE(generate_absen.telat::text, '') as generate_absen_telat"),
                        DB::raw("COALESCE(generate_absen.pa::text, '') as generate_absen_pa"),
                        DB::raw("COALESCE(generate_absen.note::text, '') as generate_absen_note"),
                        DB::raw("COALESCE(TO_CHAR(leaves.from_date, 'YYYY-MM-DD'), '') as leave_from_date"),
                        DB::raw("COALESCE(TO_CHAR(leaves.to_date, 'YYYY-MM-DD'), '') as leave_to_date"),
                        DB::raw("COALESCE(leaves.duration::text, '') as leave_duration"),
                        DB::raw("COALESCE(leaves.note, '') as leave_note"),
                        DB::raw("COALESCE(leave_types.name, '') as leave_type_name"),
                        DB::raw("COALESCE(leave_statuses.name, '') as leave_status_name"),
                        DB::raw("'' as overtime_id"),
                        DB::raw("'' as overtime_task"),
                        DB::raw("'' as overtime_note"),
                        DB::raw("COALESCE(munits.name, '') as unit_name"),
                    ])
                    ->leftJoin('employees', 'shift_schedules.employee_id', '=', 'employees.id')
                    ->leftJoin('generate_absen', function ($join) {
                        $join->on('shift_schedules.employee_id', '=', 'generate_absen.employee_id')
                            ->where(DB::raw("CAST(shift_schedules.date AS DATE)"), '=', DB::raw("CAST(generate_absen.date AS DATE)"))
                            ->where('generate_absen.type', 'ABSEN');
                    })
                    ->leftJoin('shifts', 'shift_schedules.shift_id', '=', 'shifts.id')
                    ->leftJoin('leaves', 'shift_schedules.leave_id', '=', 'leaves.id')
                    ->leftJoin('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
                    ->leftJoin('leave_statuses', 'leaves.leave_status_id', '=', 'leave_statuses.id')
                    ->leftJoin('munits', 'employees.unit_id', '=', 'munits.id')
                    ->unionAll($overtimes)
                    ->whereBetween('shift_schedules.date', [$period1, $period2])
                    ->orderBy('shift_schedule_date', 'desc')
                    ->get();
        return view('excel.shift_schedule_kehadiran', [
            'items' => $items,
            'i' => 1
        ]);
    }
}
