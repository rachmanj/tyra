<?php

namespace App\Http\Controllers;

use App\Models\Pattern;
use App\Models\Supplier;
use App\Models\Tyre;
use App\Models\TyreBrand;
use App\Models\TyreSize;
use Illuminate\Http\Request;

class TyreController extends Controller
{
    public function index()
    {
        return view('tyres.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tyres,serial_number',
        ]);

        Tyre::create($request->all());

        return redirect()->route('tyres.index')->with('success', 'Tyre created successfully.');
    }

    public function update(Request $request, $id)
    {
        $tyre = Tyre::find($id);

        $request->validate([
            'name' => 'required|unique:tyres,serial_number,' . $tyre->id,
        ]);

        $tyre->update($request->all());

        return redirect()->route('tyres.index')->with('success', 'Tyre updated successfully.');
    }

    public function destroy($id)
    {
        Tyre::find($id)->delete();

        return redirect()->route('tyres.index')->with('success', 'Tyre deleted successfully.');
    }

    public function data()
    {
        $tyres = Tyre::orderBy('serial_number', 'asc')->get();

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
}
