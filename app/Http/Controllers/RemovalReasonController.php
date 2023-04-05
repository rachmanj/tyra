<?php

namespace App\Http\Controllers;

use App\Models\RemovalReason;
use Illuminate\Http\Request;

class RemovalReasonController extends Controller
{
    public function index()
    {
        return view('removal-reasons.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|unique:removal_reasons,description',
        ]);

        RemovalReason::create($request->all());

        return redirect()->route('removal-reasons.index')->with('success', 'Removal Reason created successfully.');
    }

    public function update(Request $request, $id)
    {
        $reasons = RemovalReason::find($id);

        $request->validate([
            'description' => 'required|unique:removal_reasons,description,' . $reasons->id,
        ]);

        $reasons->update($request->all());

        return redirect()->route('removal-reasons.index')->with('success', 'Removal Reason updated successfully.');
    }

    public function destroy($id)
    {
        RemovalReason::find($id)->delete();

        return redirect()->route('removal-reasons.index')->with('success', 'Removal Reason deleted successfully.');
    }

    public function data()
    {
        $reasons = RemovalReason::orderBy('description', 'asc')->get();

        return datatables()->of($reasons)
            ->addIndexColumn()
            ->addColumn('action', 'removal-reasons.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
