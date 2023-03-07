<?php

namespace App\Http\Controllers;

use App\Models\LegalitasType;
use Illuminate\Http\Request;

class LegalitasTypeController extends Controller
{
    public function index()
    {
        return view('legalitas_types.index');
    }

    public function store(Request $request)
    {
        $this->store_process($request);

        return redirect()->route('legalitas_types.index')->with('success', 'Document Type created successfully.');
    }

    public function store_process($data)
    {
        $data->validate([
            'name' => 'required|unique:legalitas_types,name',
        ]);

        LegalitasType::create($data->all());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:legalitas_types,name,' . $id,
        ]);

        $this->update_process($request, $id);

        return redirect()->route('legalitas_types.index')->with('success', 'Document Type updated successfully.');
    }

    public function destroy($id)
    {
        $legalitas_types = LegalitasType::find($id);
        $legalitas_types->delete();

        return redirect()->route('legalitas_types.index')->with('success', 'Document Type deleted successfully.');
    }



    public function update_process($data, $id)
    {
        $data->validate([
            'name' => 'required|unique:legalitas_types,name,' . $id,
        ]);

        $legalitas_types = LegalitasType::find($id);
        $legalitas_types->update($data->all());
    }

    public function data()
    {
        $legalitas_types = LegalitasType::orderBy('name', 'asc')->get();

        return datatables()->of($legalitas_types)
            ->addIndexColumn()
            ->addColumn('action', 'legalitas_types.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
