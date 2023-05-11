<?php

namespace App\Http\Controllers;

use App\Models\Pattern;
use App\Models\RemovalReason;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\Tyre;
use App\Models\TyreBrand;
use App\Models\TyreSize;
use App\Models\User;
use Illuminate\Http\Request;

class TyreController extends Controller
{
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
        // $projects = app(ToolController::class)->getProjects();

        // return view('tyres.create', compact('sizes', 'brands', 'patterns', 'suppliers', 'projects'));
        return view('tyres.create', compact('sizes', 'brands', 'patterns', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|unique:tyres,serial_number',
            'is_new' => 'required',
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

        // $equipments = app(ToolController::class)->getEquipments($tyre->current_project);

        // check user role
        // $roles = User::find(auth()->user()->id)->getRoleNames()->toArray();
        $roles = app(ToolController::class)->getUserRoles();

        if (in_array('superadmin', $roles) || in_array('admin', $roles)) {
            $project_equipment = 'all';
        } else {
            $project_equipment = auth()->user()->project;
        }

        $removal_reasons = RemovalReason::orderBy('description', 'asc')->get();
        $last_transaction = app(ToolController::class)->getLastTransaction($tyre->id);
        $current_hm = app(ToolController::class)->getHMTyre($id);

        return view('tyres.show', compact('tyre', 'removal_reasons', 'last_transaction', 'current_hm', 'project_equipment'));
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
            'is_new' => 'required',
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

    public function activate($id)
    {
        $tyre = Tyre::find($id);

        if ($tyre->is_active == 1) {
            $tyre->is_active = 0;
        } else {
            $tyre->is_active = 1;
        }
        $tyre->save();

        return redirect()->route('tyres.show', $id)->with('success', 'Tyre activation status changed successfully.');
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
        $roles = app(ToolController::class)->getUserRoles();

        if (in_array('superadmin', $roles) || in_array('admin', $roles)) {
            $tyres = Tyre::orderBy('is_active', 'desc')->orderBy('created_at', 'desc')->get();
        } else {
            $tyres = Tyre::orderBy('is_active', 'desc')->orderBy('created_at', 'desc')
                ->where('current_project', auth()->user()->project)
                ->get();
        }

        return datatables()->of($tyres)
            ->editColumn('serial_number', function ($tyre) {
                $is_new = $tyre->is_new == 1 ? '<span class="badge badge-primary">New</span>' : '<span class="badge badge-warning">Used</span>';
                return $tyre->serial_number . ' ' . $is_new;
            })
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
            ->editColumn('is_active', function ($tyre) {
                if ($tyre->is_active == 1) {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addIndexColumn()
            ->addColumn('action', 'tyres.action')
            ->rawColumns(['action', 'serial_number', 'is_active'])
            ->toJson();
    }

    public function histories_data($id)
    {
        $histories = Transaction::where('tyre_id', $id)->orderBy('date', 'desc')->get();

        return datatables()->of($histories)
            ->editColumn('date', function ($history) {
                return date('d-M-Y', strtotime($history->date));
            })
            ->editColumn('rtd1', function ($history) {
                $rtd1 = $history->rtd1 ? $history->rtd1 : "n/a";
                $rtd2 = $history->rtd2 ? $history->rtd2 : "n/a";
                return $rtd1 . " | " . $rtd2;
            })
            ->addColumn('removal_reason', function ($history) {
                return $history->removal_reason_id ? $history->removalReason->description : "n/a";
            })
            ->addIndexColumn()
            ->addColumn('action_button', 'tyres.histories_action')
            ->rawColumns(['action_button'])
            ->toJson();
    }

    public function test()
    {
        $equipments = app(ToolController::class)->getEquipments('APS');

        return $equipments;
    }
}
