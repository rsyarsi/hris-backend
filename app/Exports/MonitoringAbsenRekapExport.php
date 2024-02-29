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
        $year = '2024';
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->format('F');
            $months[] = $monthName;
        }

        $units = Unit::all();
        $now = Carbon::now()->toDateString();
        $employees = [];
        foreach ($units as $unit) {
            $employeeCount = Employee::where('unit_id', $unit->id)
                                    ->where('started_at', '<=', $now)
                                    ->where('resigned_at', '>=', $now)
                                    ->count();
            $employees[$unit->id] = $employeeCount; // Store count for each unit ID
        }

        // $generateAbsens = GenerateAbsen::where('period', )
        //                                 ->get();

        // Pass the units, months, and employee counts array to the view
        return view('excel.rekap_absen', [
            'units' => $units,
            'months' => $months,
            'employees' => $employees,
            'year' => $year,
        ]);
    }
}
