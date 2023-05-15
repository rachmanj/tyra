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
        $last_transaction = app(ToolController::class)->getLastTransaction($request->tyre_id);
        $last_transaction_date = $last_transaction ? $last_transaction->date : '1970-01-01';

        $last_tx_of_unit_requested = Transaction::where('tyre_id', $request->tyre_id)
            ->where('unit_no', $request->unit_no)
            ->orderBy('id', 'desc')
            ->first();

        $last_hm_of_unit_requested = $last_tx_of_unit_requested ? $last_tx_of_unit_requested->hm : 0;

        $validated = $request->validate([
            'tyre_id' => 'required',
            'date' => 'required|date|after_or_equal:' . $last_transaction_date,
            'unit_no' => 'required',
            'position' => 'required',
            'hm' => 'required|numeric|min:' . $last_hm_of_unit_requested,
            'rtd1' => 'required',
            'rtd2' => 'required',
        ]);

        $tyre = Tyre::find($request->tyre_id);

        if ($request->form_type == 'show_tyre_install') {
            $tx_type = 'ON';
        } else if ($request->form_type == 'show_tyre_remove') {
            $tx_type = 'OFF';
            // get last tranasction hm of the tyre
            $last_transaction = app(ToolController::class)->getLastTransaction($tyre->id);
            // update tyre accumulated_hm at tyres table
            $tyre->update([
                'accumulated_hm' => $tyre->accumulated_hm + ($request->hm - $last_transaction->hm),
            ]);
        } else {
            $tx_type = $request->tx_type;
        }

        Transaction::create(array_merge($validated, [
            'project' => $tyre->current_project,
            'tx_type' => $tx_type,
            'removal_reason_id' => $request->removal_reason_id,
            'remark' => $request->remark,
            'created_by' => auth()->user()->id,
        ]));

        if ($request->form_type == 'show_tyre_install' || $request->form_type == 'show_tyre_remove')
            return redirect()->route('tyres.show', $request->tyre_id)->with('success', 'Transaction created successfully.');
        else {
            return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
        }
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
                return number_format($transaction->rtd1, 0) . ' | ' . number_format($transaction->rtd2, 0);
            })
            ->addColumn('removal_reason', function ($transaction) {
                return $transaction->removalReason->description;
            })
            ->editColumn('created_by', function ($transaction) {
                return $transaction->createdBy->name;
            })
            ->editColumn('created_at', function ($transaction) {
                // diffForHumans() is a Carbon method
                return $transaction->created_at->diffForHumans();
            })
            ->addIndexColumn()
            ->addColumn('action', 'transactions.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
