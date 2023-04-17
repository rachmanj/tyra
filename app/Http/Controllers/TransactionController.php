<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Tyre;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return view('transactions.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tyre_id' => 'required',
            'date' => 'required',
            'unit_no' => 'required',
            'position' => 'required',
            'hm' => 'required',
            'rtd1' => 'required',
            'rtd2' => 'required',
        ]);

        $tyre = Tyre::find($request->tyre_id);

        if ($request->form_type == 'show_tyre_install')
            $tx_type = 'ON';
        else if ($request->form_type == 'show_tyre_remove')
            $tx_type = 'OFF';
        else
            $tx_type = $request->tx_type;

        Transaction::create(array_merge($validated, [
            'project' => $tyre->current_project,
            'tx_type' => $tx_type,
            'remark' => $request->remark,
            'created_by' => auth()->user()->id
        ]));

        if ($request->form_type == 'show_tyre_install' || $request->form_type == 'show_tyre_remove')
            return redirect()->route('tyres.show', $request->tyre_id)->with('success', 'Transaction created successfully.');
        else {
            return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
        }
    }

    public function data()
    {
        $transactions = Transaction::orderBy('date', 'asc')->get();

        return datatables()->of($transactions)
            ->addColumn('tyre_sn', function ($transaction) {
                return $transaction->tyre->serial_number;
            })
            ->addColumn('removal_reason', function ($transaction) {
                return $transaction->removalReason->description;
            })
            ->addIndexColumn()
            ->addColumn('action', 'transactions.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
