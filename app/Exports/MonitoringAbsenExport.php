<?php

namespace App\Exports;

use App\Models\GenerateAbsen;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, ShouldAutoSize};

class MonitoringAbsenExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $period1 = request()->input('period_1');
        $period2 = request()->input('period_2');
        $items = GenerateAbsen::with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number', 'unit_id');
                            },
                            'shift' => function ($query) {
                                $query->select('id', 'code', 'name', 'in_time', 'out_time');
                            },
                            'leave' => function ($query) {
                                $query->select('id', 'from_date', 'to_date', 'duration', 'note');
                            },
                            'leaveType' => function ($query) {
                                $query->select('id', 'name', 'is_salary_deduction', 'active');
                            },
                            'user' => function ($query) {
                                $query->select('id', 'name', 'email');
                            },
                        ])
                        ->where(function ($subquery) {
                            $subquery->whereNull('leave_id')
                                ->orWhere('leave_id', null)
                                ->orWhere('leave_id', '');
                        })
                        ->where(function ($subquery) {
                            $subquery->whereNull('holiday')
                                ->orWhere('holiday', 0);
                        })
                        ->where(function ($subquery) {
                            $subquery->where(function ($timeQuery) {
                                $timeQuery->whereNull('time_in_at')
                                    ->whereNull('time_out_at');
                            })
                            ->orWhere(function ($timeQuery) {
                                $timeQuery->whereNotNull('time_in_at')
                                    ->whereNull('time_out_at');
                            });
                        })
                        ->whereDate('date','>=', $period1)
                        ->whereDate('date','<=', $period2)
                        ->orderBy('date', 'DESC')
                        ->get();
        return view('excel.monitoring_absen', [
            'items' => $items,
            'i' => 1
        ]);
    }
}
