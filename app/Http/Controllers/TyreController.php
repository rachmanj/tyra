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
use Carbon\Carbon;

class TyreController extends Controller
{
    protected $toolController;

    public function __construct(ToolController $toolController)
    {
        $this->toolController = $toolController;
    }

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

        // get roles of user
        $roles = app(ToolController::class)->getUserRoles();

        if (in_array('superadmin', $roles) || in_array('admin', $roles)) {
            $projects = app(ToolController::class)->getProjects();
        } else {
            $projects = app(ToolController::class)->getProjects();
            $projects = array_filter($projects, function ($item) {
                return $item['project_code'] == auth()->user()->project;
            });
        }

        return view('tyres.create', compact('sizes', 'brands', 'patterns', 'suppliers', 'projects'));
        // return view('tyres.create', compact('sizes', 'brands', 'patterns', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|unique:tyres,serial_number',
            'prod_year' => 'required',
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
            'warranty_exp_date' => $request->warranty_exp_date ?? null,
            'warranty_exp_hm' => $request->warranty_exp_hm ?? null,
            'created_by' => auth()->user()->id
        ]));

        return redirect()->route('tyres.index')->with('success', 'Tyre created successfully.');
    }

    public function show($id)
    {
        $tyre = Tyre::find($id);

        $roles = app(ToolController::class)->getUserRoles();

        if (array_intersect(['superadmin', 'admin'], $roles)) {
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

        // get roles of user
        $roles = app(ToolController::class)->getUserRoles();

        if (in_array('superadmin', $roles) || in_array('admin', $roles)) {
            $projects = app(ToolController::class)->getProjects();
        } else {
            $projects = app(ToolController::class)->getProjects();
            $projects = array_filter($projects, function ($item) {
                return $item['project_code'] == auth()->user()->project;
            });
        }

        return view('tyres.edit', compact('tyre', 'sizes', 'brands', 'patterns', 'suppliers', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $tyre = Tyre::find($id);

        $validated = $request->validate([
            'serial_number' => 'required|unique:tyres,serial_number,' . $tyre->id,
            'prod_year' => 'required',
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

        $tyre->update(array_merge($validated, [
            'warranty_exp_date' => $request->warranty_exp_date ?? null,
            'warranty_exp_hm' => $request->warranty_exp_hm ?? null,
        ]));

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
        $tyre = Tyre::find($transaction->tyre_id);

        if ($transaction->tx_type == 'OFF') {
            // find last transaction before $transaction
            $last_transaction = Transaction::where('tyre_id', $tyre->id)
                ->where('id', '<', $transaction->id)
                ->orderBy('id', 'desc')
                ->first();

            $variant_hm = $transaction->hm - $last_transaction->hm;

            // update tyre current hm
            $updated_tyre_hm = $tyre->accumulated_hm - $variant_hm;
            $tyre->update(['accumulated_hm' => $updated_tyre_hm]);
        }

        $transaction->delete();

        return redirect()->route('tyres.show', $tyre->id)->with('success', 'Transaction deleted successfully.');
    }

    public function reset_hm($id)
    {
        $tyre = Tyre::find($id);
        $last_accumulated_hm = $tyre->accumulated_hm;
        $tyre->update([
            'last_hm_before_reset' => $last_accumulated_hm,
            'accumulated_hm' => 0,
        ]);

        return redirect()->route('tyres.show', $id)->with('success', 'Tyre HM reset successfully.');
    }

    public function data()
    {
        $roles = $this->toolController->getUserRoles();

        $tyresQuery = Tyre::orderBy('is_active', 'desc')->orderBy('created_at', 'desc');

        if (!array_intersect(['superadmin', 'admin'], $roles)) {
            $tyresQuery->where('current_project', auth()->user()->project);
        }

        $tyres = $tyresQuery->with(['size', 'brand', 'pattern', 'supplier'])->get();

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
                $warrantyExpDate = $tyre->warranty_exp_date ? Carbon::parse($tyre->warranty_exp_date)->format('d-M-Y') : "n/a";
                $warrantyExpHm = $tyre->warranty_exp_hm ? number_format($tyre->warranty_exp_hm, 0) : "n/a";
                return sprintf('%s <br> %s <br> %s', number_format($tyre->hours_target, 0), $warrantyExpDate, $warrantyExpHm);
            })
            ->addColumn('cph', function ($tyre) {
                return $tyre->price && $tyre->hours_target ? number_format($tyre->price / $tyre->hours_target, 0) : "n/a";
            })
            ->editColumn('is_active', function ($tyre) {
                return $tyre->is_active == 1
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-danger">Inactive</span>';
            })
            ->addColumn('unit_no', function ($tyre) {
                $last_transaction = $this->toolController->getLastTransaction($tyre->id);
                return $last_transaction && $last_transaction->tx_type == 'ON'
                    ? $last_transaction->unit_no
                    : "n/a";
            })
            ->addIndexColumn()
            ->addColumn('action', 'tyres.action')
            ->rawColumns(['action', 'serial_number', 'is_active', 'hours_target'])
            ->toJson();
    }

    public function histories_data($id)
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
            ->addColumn('action_button', 'tyres.histories_action')
            ->rawColumns(['action_button'])
            ->toJson();
    }

    public function test()
    {
        $equipments = $this->toolController->getEquipments('APS');

        return $equipments;
    }

    public function tyre_data()
    {
        $tyres = Tyre::with(['size', 'brand', 'pattern', 'supplier'])->get();

        return datatables()->of($tyres)
            ->editColumn('is_active', function ($tyre) {
                return $tyre->is_active == 1
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-danger">Inactive</span>';
            })
            ->addColumn('unit_no', function ($tyre) {
                $last_transaction = $this->toolController->getLastTransaction($tyre->id);
                return $last_transaction && $last_transaction->tx_type == 'ON'
                    ? $last_transaction->unit_no
                    : "n/a";
            })
            ->addIndexColumn()
            ->addColumn('action', 'tyres.action')
            ->rawColumns(['action', 'serial_number', 'is_active', 'hours_target'])
            ->toJson();
    }
}
