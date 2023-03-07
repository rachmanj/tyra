<?php

namespace App\Http\Controllers;

use App\Models\Specification;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return view('suppliers.index');
    }

    public function create()
    {
        $badan_hukum = ['PT', 'CV', 'UD', 'Koperasi', 'Yayasan', 'Lainnya'];
        $specifications = Specification::orderBy('name', 'asc')->get();
        $nomor = Carbon::now()->addHours(8)->format('y') . '/PRC/REG-VENDOR/' . str_pad(Supplier::count() + 1, 3, '0', STR_PAD_LEFT);

        return view('suppliers.create', compact('nomor', 'badan_hukum', 'specifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'experience' => ['required', 'numeric', 'min:1900', 'max:2099'],
        ]);

        $supplier = new Supplier();
        $supplier->reg_no = Carbon::now()->addHours(8)->format('y') . '/PRC/REG-VENDOR/' . str_pad(Supplier::count() + 1, 3, '0', STR_PAD_LEFT);
        $supplier->name = $request->name;
        $supplier->sap_code = $request->sap_code;
        $supplier->badan_hukum = $request->badan_hukum;
        $supplier->npwp = $request->npwp;
        $supplier->experience = $request->experience;
        $supplier->jumlah_karyawan = $request->jumlah_karyawan;
        $supplier->status = $request->status;
        $supplier->remarks = $request->remarks;
        $supplier->created_by = auth()->user()->id;
        $supplier->save();

        if ($request->specifications) {
            foreach ($request->specifications as $specification) {
                $supplier->specifications()->attach($specification);
            }
        }

        return redirect()->route('suppliers.index')->with('success', 'Data berhasil disimpan');
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.show', compact('supplier'));
    }

    public function data()
    {
        $suppliers = Supplier::orderBy('name', 'asc')->get();

        return datatables()->of($suppliers)
            ->editColumn('experience', function ($suppliers) {
                $today_year = Carbon::now()->addHours(8)->format('Y');
                return $suppliers->experience ? $today_year - $suppliers->experience . ' Thn' : '-';
            })
            ->editColumn('status', function ($suppliers) {
                if ($suppliers->status == 'active') {
                    return '<span class="badge badge-success">active</span>';
                } elseif ($suppliers->status == 'banned') {
                    return '<span class="badge badge-danger">Banned</span>';
                } else {
                    return '<span class="badge badge-warning">in-active</span>';
                }
            })
            ->editColumn('name', function ($supplier) {
                return '<a href="' . route('suppliers.show', $supplier->id) . '">' . $supplier->name . ', ' . $supplier->badan_hukum . '</a>';
            })
            ->addColumn('specifications', function ($supplier) {
                $specifications = '';
                foreach ($supplier->specifications as $specification) {
                    $specifications .= '<span class="badge badge-info">' . $specification->name . '</span> ';
                }
                return $specifications;
            })
            ->addColumn('action', 'suppliers.action')
            ->addIndexColumn()
            ->rawColumns(['action', 'status', 'name', 'specifications'])
            ->toJson();
    }
}
