<?php

namespace App\Http\Controllers;

use App\Models\TyreBrand;
use Illuminate\Http\Request;

class TyreBrandController extends Controller
{
    public function index()
    {
        return view('tyre-brands.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tyre_brands,name',
        ]);

        TyreBrand::create($request->all());

        return redirect()->route('tyre-brands.index')->with('success', 'Tyre Brand created successfully.');
    }

    public function update(Request $request, $id)
    {
        $tyre_brand = TyreBrand::find($id);

        $request->validate([
            'name' => 'required|unique:tyre_brands,name,' . $tyre_brand->id,
        ]);

        $tyre_brand->update($request->all());

        return redirect()->route('tyre-brands.index')->with('success', 'Tyre Brand updated successfully.');
    }

    public function destroy($id)
    {
        TyreBrand::find($id)->delete();

        return redirect()->route('tyre-brands.index')->with('success', 'Tyre Brand deleted successfully.');
    }

    public function data()
    {
        $tyre_brands = TyreBrand::orderBy('name', 'asc')->get();

        return datatables()->of($tyre_brands)
            ->addIndexColumn()
            ->addColumn('action', 'tyre-brands.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
