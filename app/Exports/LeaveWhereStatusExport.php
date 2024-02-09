<?php

namespace App\Exports;

use App\Models\Leave;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, ShouldAutoSize};

class LeaveWhereStatusExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $period1 = request()->input('period_1');
        $period2 = request()->input('period_2');
        $status = request()->input('status');
        $items = Leave::with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number', 'unit_id');
                            },
                            'leaveType' => function ($query) {
                                $query->select('id', 'name', 'is_salary_deduction', 'active');
                            },
                            'leaveStatus' => function ($query) {
                                $query->select('id', 'name');
                            },
                        ])
                        ->whereIn('leave_status_id', $status)
                        ->whereDate('from_date','>=', $period1)
                        ->whereDate('from_date','<=', $period2)
                        ->orderBy('from_date', 'DESC')
                        ->get();
        return view('excel.leave', [
            'items' => $items,
            'i' => 1
        ]);
    }
}
