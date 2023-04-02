<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Document;
use App\Models\LegalitasType;
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
        $badan_hukum = ['PT', 'CV', 'UD', 'Koperasi', 'Yayasan', 'Perorangan', 'Lainnya'];
        $specifications = Specification::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        $document_types = LegalitasType::orderBy('name', 'asc')->get();
        $nomor = $this->nomor_registrasi();

        return view('suppliers.create', compact('nomor', 'badan_hukum', 'specifications', 'brands', 'document_types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'experience' => ['required', 'numeric', 'min:1900', 'max:2099'],
            'badan_hukum' => 'required',
        ]);

        $supplier = new Supplier();
        $supplier->reg_no = $this->nomor_registrasi();
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

        // if email is not empty
        $emailArray = explode(',', $request->emails);
        if ($emailArray) {
            foreach ($emailArray as $email) {
                $supplier->emails()->create([
                    'email' => $email,
                    'created_by' => auth()->user()->id,
                ]);
            }
        }

        // if specification is not empty
        if ($request->specifications) {
            foreach ($request->specifications as $specification) {
                $supplier->specifications()->attach($specification);
            }
        }

        // if brand is not empty
        if ($request->brands) {
            foreach ($request->brands as $brand) {
                $supplier->brands()->attach($brand);
            }
        }

        // save branches
        if ($request->branches) {
            foreach ($request->branches as $branch) {
                $supplier->branches()->create([
                    'address1' => $branch['address1'],
                    'address2' => $branch['address2'],
                    'city' => $branch['city'],
                    'province' => $branch['province'],
                    'postal_code' => $branch['postal_code'],
                    'phone' => $branch['phone'],
                    'email' => $branch['email'],
                ]);
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

    public function nomor_registrasi()
    {
        return str_pad(Supplier::count() + 1, 3, '0', STR_PAD_LEFT) . '/PRC/REG-VENDOR/' . Carbon::now()->addHours(8)->format('y');
    }
}
