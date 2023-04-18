<?php

namespace App\Http\Controllers;

use App\Models\Pattern;
use App\Models\RemovalReason;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\Tyre;
use App\Models\TyreBrand;
use App\Models\TyreSize;
use Illuminate\Http\Request;

class TyreController extends Controller
{
    /*
    public function __construct()
    {
        // get equipment from arkFleet
        $url = env('URL_EQUIPMENTS');
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $equipments = json_decode($response->getBody()->getContents(), true)['data'];

        // get projects from arkFleet
        $url = env('URL_PROJECTS');
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $projects = json_decode($response->getBody()->getContents(), true)['data'];

        view()->share('equipments', $equipments);

        view()->share('projects', $projects);
    }
    */

    public function index()
    {
        return view('tyres.index');
    }

    public function create()
    {
        $sizes = TyreSize::orderBy('description', 'asc')->get();
        $brands = TyreBrand::orderBy('name', 'asc')->get();
        $patterns = Pattern::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        $projects = app(ToolController::class)->getProjects();

        return view('tyres.create', compact('sizes', 'brands', 'patterns', 'suppliers', 'projects'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'serial_number' => 'required|unique:tyres,serial_number',
            'po_no' => 'required',
            'size_id' => 'required',
            'brand_id' => 'required',
            'pattern_id' => 'required',
            'supplier_id' => 'required',
            'current_project' => 'required',
            'otd' => 'required|numeric',
            'price' => 'required|numeric',
            'hours_target' => 'required|numeric',
            'pressure' => 'required|numeric',
        ]);

        Tyre::create(array_merge($request->all(), [
            'created_by' => auth()->user()->id
        ]));

        return redirect()->route('tyres.index')->with('success', 'Tyre created successfully.');
    }

    public function show($id)
    {
        $tyre = Tyre::find($id);
        $equipments = app(ToolController::class)->getEquipments($tyre->current_project);
        $removal_reasons = RemovalReason::orderBy('description', 'asc')->get();
        $last_transaction = app(ToolController::class)->getLastTransaction($tyre->id);
        $current_hm = app(ToolController::class)->getHMTyre($id);

        return view('tyres.show', compact('tyre', 'equipments', 'removal_reasons', 'last_transaction', 'current_hm'));
    }

    public function edit($id)
    {
        $tyre = Tyre::find($id);
        $sizes = TyreSize::orderBy('description', 'asc')->get();
        $brands = TyreBrand::orderBy('name', 'asc')->get();
        $patterns = Pattern::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();

        return view('tyres.edit', compact('tyre', 'sizes', 'brands', 'patterns', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $tyre = Tyre::find($id);

        $request->validate([
            'serial_number' => 'required|unique:tyres,serial_number,' . $tyre->id,
            'po_no' => 'required',
            'size_id' => 'required',
            'brand_id' => 'required',
            'pattern_id' => 'required',
            'supplier_id' => 'required',
            'current_project' => 'required',
            'otd' => 'required|numeric',
            'price' => 'required|numeric',
            'hours_target' => 'required|numeric',
            'pressure' => 'required|numeric',
        ]);

        $tyre->update($request->all());

        return redirect()->route('tyres.index')->with('success', 'Tyre updated successfully.');
    }

    public function destroy($id)
    {
        Tyre::find($id)->delete();

        return redirect()->route('tyres.index')->with('success', 'Tyre deleted successfully.');
    }

    public function transaction_destroy($transaction_id)
    {
        $transaction = Transaction::find($transaction_id);
        $tyre_id = $transaction->tyre_id;
        $transaction->delete();

        return redirect()->route('tyres.show', $tyre_id)->with('success', 'Transaction deleted successfully.');
    }

    public function data()
    {
        $tyres = Tyre::orderBy('created_at', 'desc')->get();

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
            ->editColumn('hours_target', function ($tyre) {
                return number_format($tyre->hours_target, 0);
            })
            ->addColumn('cph', function ($tyre) {
                if ($tyre->price && $tyre->hours_target) {
                    return number_format($tyre->price / $tyre->hours_target, 0);
                } else {
                    return "n/a";
                }
            })
            ->addIndexColumn()
            ->addColumn('action', 'tyres.action')
            ->rawColumns(['action'])
            ->toJson();
    }

    public function histories_data($id)
    {
        $histories = Transaction::where('tyre_id', $id)->orderBy('date', 'desc')->get();

        return datatables()->of($histories)
            ->editColumn('date', function ($history) {
                return date('d-M-Y', strtotime($history->date));
            })
            ->addIndexColumn()
            ->addColumn('action_button', 'tyres.histories_action')
            ->rawColumns(['action_button'])
            ->toJson();
    }
}