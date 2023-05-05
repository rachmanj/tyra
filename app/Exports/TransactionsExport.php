<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransactionsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();

        return view('reports.transactions.export', compact('transactions'));
    }
}
