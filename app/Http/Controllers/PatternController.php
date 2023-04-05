<?php

namespace App\Http\Controllers;

use App\Models\Pattern;
use Illuminate\Http\Request;

class PatternController extends Controller
{
    public function index()
    {
        return view('patterns.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:patterns,name',
        ]);

        Pattern::create($request->all());

        return redirect()->route('patterns.index')->with('success', 'Pattern created successfully.');
    }

    public function update(Request $request, $id)
    {
        $pattern = Pattern::find($id);

        $request->validate([
            'name' => 'required|unique:patterns,name,' . $pattern->id,
        ]);

        $pattern->update($request->all());

        return redirect()->route('patterns.index')->with('success', 'Pattern updated successfully.');
    }

    public function destroy($id)
    {
        Pattern::find($id)->delete();

        return redirect()->route('patterns.index')->with('success', 'Pattern deleted successfully.');
    }

    public function data()
    {
        $patterns = Pattern::orderBy('name', 'asc')->get();

        return datatables()->of($patterns)
            ->addIndexColumn()
            ->addColumn('action', 'patterns.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
