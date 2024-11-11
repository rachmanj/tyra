<?php

namespace App\Http\Controllers;

use App\Exports\TyreRekapExport;
use App\Models\Transaction;
use App\Models\Tyre;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function tyre_rekaps()
    {
        return view('reports.tyre-rekaps.index');
    }

    public function tyre_rekaps_show($id)
    {
        $tyre = Tyre::findOrFail($id);

        return view('reports.tyre-rekaps.show', compact('tyre'));
    }

    public function tyre_rekaps_export()
    {
        return Excel::download(new TyreRekapExport, 'tyres_rekaps.xlsx');
    }

    public function tyre_rekaps_data()
    {
        $tyres = Tyre::with(['lastTransaction'])->get();

        return datatables()->of($tyres)
            ->addColumn('size', function ($tyre) {
                return $tyre->size->description;
            })
            ->addColumn('brand', function ($tyre) {
                return $tyre->brand->name;
            })
            ->addColumn('pattern', function ($tyre) {
                return $tyre->pattern->name;
            })
            ->addColumn('vendor', function ($tyre) {
                return $tyre->supplier->name;
            })
            ->editColumn('price', function ($tyre) {
                return number_format($tyre->price, 0);
            })
            ->editColumn('hm_target', function ($tyre) {
                return number_format($tyre->hours_target, 0);
            })
            ->addColumn('cph_target', function ($tyre) {
                return $tyre->price && $tyre->hours_target ? number_format($tyre->price / $tyre->hours_target, 0) : "n/a";
            })
            ->editColumn('hm_real', function ($tyre) {
                return $tyre->accumulated_hm ? number_format($tyre->accumulated_hm, 0) : "n/a";
            })
            ->addColumn('cph_real', function ($tyre) {
                return $tyre->price && $tyre->accumulated_hm ? number_format($tyre->price / $tyre->accumulated_hm, 0) : "n/a";
            })
            ->addColumn('rtd', function ($tyre) {
                $lastTransaction = $tyre->lastTransaction;
                $rtd1 = $lastTransaction ? $lastTransaction->rtd1 : "n/a";
                $rtd2 = $lastTransaction ? $lastTransaction->rtd2 : "n/a";
                return "$rtd1 | $rtd2";
            })
            ->addIndexColumn()
            ->addColumn('action', 'reports.tyre-rekaps.action')
            ->rawColumns(['action'])
            ->toJson();
    }

    public function tyre_rekaps_history_data($id)
    {
        $histories = Transaction::where('tyre_id', $id)->orderBy('date', 'desc')->get();

        return datatables()->of($histories)
            ->editColumn('date', function ($history) {
                return Carbon::parse($history->date)->format('d-M-Y');
            })
            ->editColumn('rtd1', function ($history) {
                $rtd1 = $history->rtd1 ?? "n/a";
                $rtd2 = $history->rtd2 ?? "n/a";
                return "$rtd1 | $rtd2";
            })
            ->addColumn('removal_reason', function ($history) {
                return $history->removal_reason_id ? $history->removalReason->description : "n/a";
            })
            ->addIndexColumn()
            ->toJson();
    }
}
