<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return view('suppliers.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:suppliers,name',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Vendor created successfully.');
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);

        $request->validate([
            'name' => 'required|unique:suppliers,name,' . $supplier->id,
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Vendor updated successfully.');
    }

    public function destroy($id)
    {
        Supplier::find($id)->delete();

        return redirect()->route('suppliers.index')->with('success', 'Vendor deleted successfully.');
    }

    public function data()
    {
        $suppliers = Supplier::orderBy('name', 'asc')->get();

        return datatables()->of($suppliers)
            ->addIndexColumn()
            ->addColumn('action', 'suppliers.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
