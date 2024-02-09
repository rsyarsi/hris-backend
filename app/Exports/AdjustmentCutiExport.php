<?php

namespace App\Exports;

use App\Models\AdjustmentCuti;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, ShouldAutoSize};

class AdjustmentCutiExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $year = request()->input('year');
        $items = AdjustmentCuti::with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            }
                        ])
                        ->where('year', $year)
                        ->get();
        return view('excel.adjustment_cuti', [
            'items' => $items,
            'i' => 1
        ]);
    }
}
