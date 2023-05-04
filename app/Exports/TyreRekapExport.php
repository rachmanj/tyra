<?php

namespace App\Exports;

use App\Models\Tyre;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TyreRekapExport implements FromView
{
    public function view(): View
    {
        $tyres = Tyre::orderBy('created_at', 'desc')->get();

        return view('reports.tyre-rekaps.export', compact('tyres'));
    }
}
