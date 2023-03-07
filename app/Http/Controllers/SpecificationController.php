<?php

namespace App\Http\Controllers;

use App\Models\Specification;
use Illuminate\Http\Request;

class SpecificationController extends Controller
{
    public function index()
    {
        return view('specifications.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:specifications,name',
        ]);

        $this->store_process($request);

        return redirect()->route('specifications.index')->with('success', 'Specification created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:specifications,name,' . $id,
        ]);

        $this->update_process($request, $id);

        return redirect()->route('specifications.index')->with('success', 'Specification updated successfully.');
    }

    public function destroy($id)
    {
        $specification = Specification::find($id);
        $specification->delete();

        return redirect()->route('specifications.index')->with('success', 'Specification deleted successfully.');
    }

    public function store_process($data)
    {
        $data->validate([
            'name' => 'required|unique:specifications,name',
        ]);

        Specification::create($data->all());
    }

    public function update_process($data, $id)
    {
        $data->validate([
            'name' => 'required|unique:specifications,name,' . $id,
        ]);

        $specification = Specification::find($id);
        $specification->update($data->all());
    }

    public function data()
    {
        $specifications = Specification::orderBy('name', 'asc')->get();

        return datatables()->of($specifications)
            ->addIndexColumn()
            ->addColumn('action', 'specifications.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
