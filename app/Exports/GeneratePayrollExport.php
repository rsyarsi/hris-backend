<?php

namespace App\Exports;

use App\Models\GeneratePayroll;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, ShouldAutoSize};

class GeneratePayrollExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $period = request()->input('period');
        $items = GeneratePayroll::with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            }
                        ])
                        ->where('period_payroll', $period)
                        ->get();
        return view('excel.generate_payroll', [
            'items' => $items,
            'i' => 1
        ]);
    }
}
