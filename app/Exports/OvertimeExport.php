<?php

namespace App\Exports;

use App\Models\Overtime;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, ShouldAutoSize};

class OvertimeExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $period1 = request()->input('period_1');
        $period2 = request()->input('period_2');
        $items = Overtime::with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number', 'unit_id');
                            },
                            'overtimeStatus' => function ($query) {
                                $query->select('id', 'name');
                            },
                        ])
                        ->whereDate('from_date','>=', $period1)
                        ->whereDate('from_date','<=', $period2)
                        ->orderBy('from_date', 'DESC')
                        ->get();
        return view('excel.overtime', [
            'items' => $items,
            'i' => 1
        ]);
    }
}
