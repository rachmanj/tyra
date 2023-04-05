<?php

namespace App\Http\Controllers;

use App\Models\TyreSize;
use Illuminate\Http\Request;

class TyreSizeController extends Controller
{
    public function index()
    {
        return view('tyre-sizes.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|unique:tyre_sizes,description',
        ]);

        TyreSize::create($request->all());

        return redirect()->route('tyre-sizes.index')->with('success', 'Tyre Size created successfully.');
    }

    public function update(Request $request, $id)
    {
        $tyre_size = TyreSize::find($id);

        $request->validate([
            'description' => 'required|unique:tyre_sizes,description,' . $tyre_size->id,
        ]);

        $tyre_size->update($request->all());

        return redirect()->route('tyre-sizes.index')->with('success', 'Tyre Size updated successfully.');
    }

    public function destroy($id)
    {
        TyreSize::find($id)->delete();

        return redirect()->route('tyre-sizes.index')->with('success', 'Tyre Size deleted successfully.');
    }

    public function data()
    {
        $tyre_sizes = TyreSize::orderBy('description', 'asc')->get();

        return datatables()->of($tyre_sizes)
            ->addIndexColumn()
            ->addColumn('action', 'tyre-sizes.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
