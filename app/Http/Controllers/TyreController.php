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
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TyreController extends Controller
{
    protected $toolController;

    public function __construct(ToolController $toolController)
    {
        $this->toolController = $toolController;
    }

    public function index()
    {
        $page = request()->query('page', 'search');

        $brands = TyreBrand::orderBy('name', 'asc')->get();
        $patterns = Pattern::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        $sizes = TyreSize::orderBy('description', 'asc')->get();

        $views = [
            'search' => 'tyres.search',
            'new' => 'tyres.create',
            'list' => 'tyres.list',
        ];

        $data = [];

        if ($page === 'search') {
            $projects = app(ToolController::class)->getProjects();

            $data = [
                'brands' => $brands,
                'patterns' => $patterns,
                'suppliers' => $suppliers,
                'projects' => $projects,
            ];
        } elseif ($page === 'new') {

            $roles = app(ToolController::class)->getUserRoles();

            if (array_intersect($roles, ['superadmin', 'admin'])) {
                $projects = app(ToolController::class)->getProjects();
            } else {
                $projects = app(ToolController::class)->getProjects();
                $projects = array_filter($projects, function ($item) {
                    return $item['project_code'] == auth()->user()->project;
                });
            }

            $data = [
                'sizes' => $sizes,
                'brands' => $brands,
                'patterns' => $patterns,
                'suppliers' => $suppliers,
                'projects' => $projects,
            ];
        }

        return view($views[$page], $data);
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

        $tyre = Tyre::create(array_merge($request->all(), [
            'warranty_exp_date' => $request->warranty_exp_date ?? null,
            'warranty_exp_hm' => $request->warranty_exp_hm ?? null,
            'created_by' => auth()->user()->id
        ]));

        // create activity log
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'model_name' => 'Tyre',
            'model_id' => $tyre->id,
            'activity' => "Tyre {$request->serial_number} created successfully.",
        ]);

        return redirect()->route('tyres.index', ['page' => 'new'])->with('success', 'Tyre created successfully.');
    }

    public function show($id)
    {
        $tyre = Tyre::find($id);

        if (!$tyre) {
            return redirect()->back()->with('error', 'Tyre not found');
        }

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

        // create activity log
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'model_name' => 'Tyre',
            'model_id' => $id,
            'activity' => "Tyre {$tyre->serial_number} updated successfully.",
        ]);

        return redirect()->route('tyres.index', ['page' => 'search'])->with('success', 'Tyre updated successfully.');
    }

    public function destroy($id)
    {
        // Check if user has permission to delete tyre
        if (!auth()->user()->can('delete_tyre')) {
            return redirect()->back()->with('error', 'You do not have permission to delete tyres.');
        }

        $tyre = Tyre::find($id);

        // Check if tyre exists
        if (!$tyre) {
            return redirect()->back()->with('error', 'Tyre not found.');
        }

        // Check if tyre has transactions
        if ($tyre->transactions->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete tyre with transactions.');
        }

        // Log the tyre deletion activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'model_name' => 'Tyre',
            'model_id' => $id,
            'activity' => sprintf(
                'Deleted tyre %s (Brand: %s, Pattern: %s, Size: %s)',
                $tyre->serial_number,
                $tyre->brand->name,
                $tyre->pattern->name,
                $tyre->size->description
            )
        ]);

        $tyre->delete();

        return redirect()->route('tyres.index')->with('success', 'Tyre deleted successfully.');
    }

    public function activate($id)
    {
        $tyre = Tyre::find($id);

        if (!$tyre) {
            return redirect()->back()->with('error', 'Tyre not found');
        }

        // If activating (from inactive to active), clear inactive reason
        if ($tyre->is_active == 0) {
            $tyre->update([
                'is_active' => 1,
                'inactive_reason' => null,
                'inactive_date' => null,
                'inactive_notes' => null,
            ]);

            ActivityLog::create([
                'user_id' => auth()->user()->id,
                'model_name' => 'Tyre',
                'model_id' => $id,
                'activity' => "Tyre activated successfully",
            ]);

            return redirect()->route('tyres.show', $id)->with('success', 'Tyre activated successfully.');
        }

        // If trying to deactivate, redirect to inactive form
        return redirect()->route('tyres.show', $id)->with('info', 'Please use the inactive button to deactivate tyre with reason.');
    }

    public function inactive(Request $request, $id)
    {
        $request->validate([
            'inactive_reason' => 'required|in:Scrap,Breakdown,Repair',
            'inactive_notes' => 'nullable|string|max:1000',
        ]);

        $tyre = Tyre::find($id);

        if (!$tyre) {
            return redirect()->back()->with('error', 'Tyre not found');
        }

        // Check if tyre is currently ON equipment
        $last_transaction = app(ToolController::class)->getLastTransaction($tyre->id);
        if ($last_transaction && $last_transaction->tx_type == 'ON') {
            return redirect()->back()->with('error', 'Cannot deactivate tyre that is currently installed on equipment. Please remove it first.');
        }

        $tyre->update([
            'is_active' => 0,
            'inactive_reason' => $request->inactive_reason,
            'inactive_date' => now(),
            'inactive_notes' => $request->inactive_notes,
        ]);

        // create activity log
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'model_name' => 'Tyre',
            'model_id' => $id,
            'activity' => "Tyre deactivated with reason: {$request->inactive_reason}" .
                ($request->inactive_notes ? " - Notes: {$request->inactive_notes}" : ""),
        ]);

        return redirect()->route('tyres.show', $id)->with('success', 'Tyre deactivated successfully.');
    }

    public function transaction_destroy($transaction_id)
    {
        try {
            $transaction = Transaction::find($transaction_id);
            if (!$transaction) {
                return redirect()->back()->with('error', 'Transaction not found.');
            }

            $tyre = Tyre::find($transaction->tyre_id);
            if (!$tyre) {
                return redirect()->back()->with('error', 'Tyre not found.');
            }

            $old_hm = $tyre->accumulated_hm;

            if ($transaction->tx_type == 'OFF' || $transaction->tx_type == 'UHM') {
                // find last transaction before $transaction
                $last_transaction = Transaction::where('tyre_id', $tyre->id)
                    ->where('id', '<', $transaction->id)
                    ->orderBy('id', 'desc')
                    ->first();

                // Check if there's a previous transaction
                if (!$last_transaction) {
                    // If no previous transaction exists, just set HM to 0
                    $tyre->update(['accumulated_hm' => 0]);

                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'model_name' => 'Tyre',
                        'model_id' => $tyre->id,
                        'activity' => sprintf(
                            'HM reset to 0 due to first transaction deletion (ID: %d)',
                            $transaction->id
                        )
                    ]);
                } else {
                    $variant_hm = $transaction->hm - $last_transaction->hm;

                    // update tyre current hm
                    $updated_tyre_hm = $tyre->accumulated_hm - $variant_hm;
                    $tyre->update(['accumulated_hm' => $updated_tyre_hm]);

                    // Log the HM change activity
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'model_name' => 'Tyre',
                        'model_id' => $tyre->id,
                        'activity' => sprintf(
                            'HM updated from %s to %s due to transaction deletion (ID: %d)',
                            number_format($old_hm, 0, ',', '.'),
                            number_format($updated_tyre_hm, 0, ',', '.'),
                            $transaction->id
                        )
                    ]);
                }
            }

            // Log the transaction deletion activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'model_name' => 'Transaction',
                'model_id' => $transaction_id,
                'activity' => sprintf(
                    'Deleted transaction (ID: %d) for tyre %s - Type: %s, Unit: %s, HM: %s',
                    $transaction->id,
                    $tyre->serial_number,
                    $transaction->tx_type,
                    $transaction->unit_no ?? 'N/A',
                    number_format($transaction->hm, 0, ',', '.')
                )
            ]);

            $transaction->delete();

            return redirect()->route('tyres.show', $tyre->id)->with('success', 'Transaction deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting transaction: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting transaction. Please try again.');
        }
    }

    public function reset_hm($id)
    {
        $tyre = Tyre::find($id);
        $last_accumulated_hm = $tyre->accumulated_hm;
        $tyre->update([
            'last_hm_before_reset' => $last_accumulated_hm,
            'accumulated_hm' => 0,
        ]);

        // create activity log
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'model_name' => 'Tyre',
            'model_id' => $id,
            'activity' => "Tyre HM reset successfully. Last HM before reset: $last_accumulated_hm",
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
                if ($tyre->is_active == 1) {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    $reason = $tyre->inactive_reason ? " ({$tyre->inactive_reason})" : '';
                    return '<span class="badge badge-danger" title="' . ($tyre->inactive_notes ?? '') . '">Inactive' . $reason . '</span>';
                }
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
        $histories = Transaction::where('tyre_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return datatables()->of($histories)
            ->addIndexColumn()
            ->editColumn('date', function ($history) {
                return date('d-M-Y', strtotime($history->date));
            })
            ->editColumn('tx_type', function ($history) {
                if ($history->tx_type === 'UHM') {
                    return '<span data-toggle="tooltip" title="' . e($history->remark) . '">UHM</span>';
                }
                return $history->tx_type;
            })
            ->editColumn('rtd1', function ($history) {
                $rtd1 = $history->rtd1 ?? "n/a";
                $rtd2 = $history->rtd2 ?? "n/a";
                return "$rtd1 | $rtd2";
            })
            ->addColumn('removal_reason', function ($history) {
                return $history->removal_reason_id ? $history->removalReason->description : "n/a";
            })
            ->addColumn('action_button', 'tyres.histories_action')
            ->rawColumns(['action_button', 'tx_type'])
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

    public function searchData(Request $request)
    {
        $query = Tyre::with(['brand', 'pattern', 'supplier']);

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('brand_name', function ($tyre) {
                return $tyre->brand->name ?? 'N/A';
            })
            ->addColumn('pattern_name', function ($tyre) {
                return $tyre->pattern->name ?? 'N/A';
            })
            ->addColumn('supplier_name', function ($tyre) {
                return $tyre->supplier->name ?? 'N/A';
            })
            ->editColumn('price', function ($tyre) {
                return number_format($tyre->price, 0, ',', '.');
            })
            ->addColumn('is_active', function ($tyre) {
                if ($tyre->is_active == 1) {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    $reason = $tyre->inactive_reason ? " ({$tyre->inactive_reason})" : '';
                    return '<span class="badge badge-danger" title="' . ($tyre->inactive_notes ?? '') . '">Inactive' . $reason . '</span>';
                }
            })
            ->addColumn('action', function ($tyre) {
                $html = '<a href="' . route('tyres.edit', $tyre->id) . '" class="btn btn-xs btn-info" title="Edit Tyre">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="' . route('tyres.show', $tyre->id) . '" class="btn btn-xs btn-primary" title="View Tyre Detail">
                            <i class="fas fa-eye"></i>
                        </a>';

                // Only add delete button if user has permission
                if (auth()->user()->can('delete_tyre')) {
                    $html .= '<form action="' . route('tyres.destroy', $tyre->id) . '" method="POST" class="d-inline">'
                        . csrf_field()
                        . method_field('DELETE');

                    if ($tyre->transactions->count() > 0) {
                        $html .= '<button class="btn btn-xs btn-danger" disabled>delete</button>';
                    } else {
                        $html .= '<button type="submit" class="btn btn-xs btn-danger"
                                    onclick="return confirm(\'Are you sure you want delete this record?\')"
                                >delete</button>';
                    }

                    $html .= '</form>';
                }

                return $html;
            })
            ->rawColumns(['is_active', 'action'])
            ->filter(function ($query) use ($request) {
                if ($request->filled('serial_number')) {
                    $query->where('serial_number', 'like', '%' . $request->serial_number . '%');
                }
                if ($request->filled('brand')) {
                    $query->where('brand_id', $request->brand);
                }
                if ($request->filled('pattern')) {
                    $query->where('pattern_id', $request->pattern);
                }
                if ($request->filled('supplier')) {
                    $query->where('supplier_id', $request->supplier);
                }
                if ($request->filled('project')) {
                    $query->where('current_project', $request->project);
                }
                if ($request->filled('status')) {
                    $query->where('is_active', $request->status);
                }
                if ($request->filled('po_no')) {
                    $query->where('po_no', 'like', '%' . $request->po_no . '%');
                }
                if ($request->filled('inactive_reason')) {
                    $query->where('inactive_reason', $request->inactive_reason);
                }
            })
            ->toJson();
    }

    public function getAvgCph($brand_id)
    {
        try {
            $tyres = Tyre::where('brand_id', $brand_id)
                ->where('is_active', 1)
                ->get();

            $total_price = $tyres->sum('price');
            $total_hm = $tyres->sum('accumulated_hm');

            $avg_cph = $total_hm > 0 ? $total_price / $total_hm : 0;

            return response()->json([
                'success' => true,
                'avg_cph' => round($avg_cph, 2)
            ]);
        } catch (\Exception $e) {
            Log::error('Error calculating Avg CPH: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error calculating Avg CPH'
            ], 500);
        }
    }

    public function search()
    {
        $brands = TyreBrand::orderBy('name', 'asc')->get();
        $patterns = Pattern::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        $projects = app(ToolController::class)->getProjects();

        return view('tyres.search', compact(
            'brands',
            'patterns',
            'suppliers',
            'projects'
        ));
    }
}
