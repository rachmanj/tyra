<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Tyre;
use Illuminate\Http\Request;
use App\Models\RemovalReason;

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

        // create activity log
        ActivityLog::create([
            'user_id' => auth()->user()->id,
           'model_name' => 'Transaction',
           'model_id' => $transaction->id,
           'activity' => "Created new transaction with type $tx_type for tyre {$tyre->serial_number} on unit {$validated['unit_no']}",
        ]);

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

    public function updateHm(Request $request, $id)
    {
        try {
            \DB::beginTransaction();

            // Validate request
            $request->validate([
                'last_hm' => 'required|numeric|min:0'
            ]);

            // Get the tyre
            $tyre = Tyre::findOrFail($id);
            
            // Get last transaction
            $last_transaction = app(ToolController::class)->getLastTransaction($id);
            
            // Validate HM is not less than last transaction
            if ($last_transaction && $request->last_hm < $last_transaction->hm) {
                return response()->json([
                    'success' => false,
                    'message' => 'New HM cannot be less than last transaction HM'
                ], 422);
            }

            $removal_reason_id = RemovalReason::where('description', 'HM UPDATED')->first()?->id ?? null;

            // Create new transaction
            $transaction = Transaction::create([
                'tyre_id' => $id,
                'date' => now(),
                'unit_no' => $last_transaction->unit_no,
                'tx_type' => 'UHM',
                'position' => $last_transaction->position,
                'hm' => $request->last_hm,
                'rtd1' => $last_transaction->rtd1,
                'rtd2' => $last_transaction->rtd2,
                'project' => $last_transaction->project,
                'remark' => 'HM updated from ' . ($last_transaction ? $last_transaction->hm : 0) . ' to ' . $request->last_hm . ' by ' . auth()->user()->name,
                'removal_reason_id' => $removal_reason_id,
                'created_by' => auth()->id()
            ]);

            // Update tyre accumulated_hm
            $tyre->update([
                'accumulated_hm' => $tyre->accumulated_hm + ($request->last_hm - $last_transaction->hm),
            ]);

            // create activity log
            ActivityLog::create([
                'user_id' => auth()->user()->id,
                'model_name' => 'Transaction',
                'model_id' => $transaction->id,
                'activity' => "Updated HM to $request->last_hm for tyre {$tyre->serial_number} on unit {$last_transaction->unit_no}",
            ]);

            // Calculate new CPH
            $tyre_cph = $tyre->accumulated_hm > 0 ? $tyre->price / $tyre->accumulated_hm : 0;

            // Get brand average CPH
            $avg_cph = 0;
            $tyres = Tyre::where('brand_id', $tyre->brand_id)
                ->where('is_active', 1)
                ->get();            
            if ($tyres->count() > 0) {
                $total_price = $tyres->sum('price');
                $total_hm = $tyres->sum('accumulated_hm');
                $avg_cph = $total_hm > 0 ? $total_price / $total_hm : 0;
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'accumulated_hm' => $tyre->accumulated_hm,
                'tyre_cph' => round($tyre_cph, 2),
                'avg_cph' => round($avg_cph, 2)
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Error in updateHm: ' . $e->getMessage());
            \Log::error('Tyre ID: ' . $id);
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error updating HM: ' . $e->getMessage()
            ], 500);
        }
    }
}
