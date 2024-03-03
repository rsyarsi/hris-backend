<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\GenerateAbsen;
use Carbon\Carbon;
use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, ShouldAutoSize};

class MonitoringAbsenRekapExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $year = request()->input('year');
        $months = [];
        $monthNames = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthPeriod = Carbon::createFromDate($year, $i, 1)->format('Y-m');
            $months[] = $monthPeriod;

            $monthNameString = Carbon::createFromDate($year, $i, 1)->format('F');
            $monthNames[] = $monthNameString;
        }

        $units = Unit::all();
        $employees = [];
        $absences = [];

        foreach ($units as $unit) {
            $employeeCount = Employee::where('unit_id', $unit->id)
                ->where('started_at', '<=', now())
                ->where(function ($query) {
                    $query->whereNull('resigned_at')->orWhere('resigned_at', '>=', now());
                })
                ->count();

            $employees[$unit->id] = $employeeCount;

            foreach ($months as $month) {
                $absenceCount = GenerateAbsen::whereHas('employee', function ($query) use ($unit) {
                        $query->where('unit_id', $unit->id);
                    })
                    ->where('type', 'ABSEN')
                    ->whereNull('leave_id')
                    ->where('telat', '<=', 60)
                    ->where('period', $month)
                    ->count();

                $absences[$unit->id][$month] = $absenceCount;
            }
        }

        return view('excel.rekap_absen', [
            'units' => $units,
            'months' => $months,
            'monthNames' => $monthNames,
            'employees' => $employees,
            'absences' => $absences,
            'year' => $year,
        ]);
    }
}
