<?php

namespace App\Exports;

use App\Models\GenerateAbsen;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, ShouldAutoSize};

class MonitoringAbsenRekapExport implements FromView, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('excel.rekap_absen', [
            'data' => $this->data,
        ]);
    }
}
