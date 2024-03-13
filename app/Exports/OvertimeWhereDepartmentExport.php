<?php

namespace App\Exports;

use App\Models\Overtime;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, ShouldAutoSize};

class OvertimeWhereDepartmentExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $period1 = request()->input('period_1');
        $period2 = request()->input('period_2');
        $department = request()->input('department');
        $items = Overtime::with([
                            'employee:id,name,employment_number,department_id',
                            'overtimeStatus:id,name',
                        ])
                        ->whereHas('employee', function ($employeeQuery) use ($department) {
                            $employeeQuery->where('department_id', $department);
                        })
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
