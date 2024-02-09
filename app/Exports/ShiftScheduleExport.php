<?php

namespace App\Exports;

use App\Models\ShiftSchedule;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, ShouldAutoSize};

class ShiftScheduleExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $period1 = request()->input('period_1');
        $period2 = request()->input('period_2');
        $items = ShiftSchedule::with([
                                'employee' => function ($query) {
                                    $query->select('id', 'name', 'employment_number');
                                },
                                'shift' => function ($query) {
                                    $query->select('id', 'code', 'name', 'in_time', 'out_time');
                                }
                            ])
                            ->whereDate('date','>=', $period1)
                            ->whereDate('date','<=', $period2)
                            ->orderBy('date', 'DESC')
                            ->get();
        return view('excel.shift_schedule', [
            'items' => $items,
            'i' => 1
        ]);
    }
}
