<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Models\Transaction;
use Maatwebsite\Excel\Facades\Excel;

class ReportTransactionController extends Controller
{
    public function index()
    {
        return view('reports.transactions.index');
    }

    public function export()
    {
        return Excel::download(new TransactionsExport, 'transaction_rekaps.xlsx');
    }

    public function data()
    {
        $transactions = Transaction::orderBy('date', 'desc')->get();

        return datatables()->of($transactions)
            ->addColumn('tyre_sn', function ($transaction) {
                return $transaction->tyre->serial_number;
            })
            ->editColumn('date', function ($transaction) {
                return date('d-M-Y', strtotime($transaction->date));
            })
            ->editColumn('hm', function ($transaction) {
                return number_format($transaction->hm, 0);
            })
            ->addColumn('rtd', function ($transaction) {
                return $transaction->rtd1 . ' | ' . $transaction->rtd2;
            })
            ->addColumn('removal_reason', function ($transaction) {
                return $transaction->removalReason->description;
            })
            ->addIndexColumn()
            ->toJson();
    
    }

}
