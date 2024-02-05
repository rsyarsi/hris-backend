<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, ShouldAutoSize};

class UsersExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('excel.user', [
            'items' => User::orderBy('name', 'ASC')->get(),
            'i' => 1
        ]);
    }
}
