<?php

namespace App\Http\Controllers;

use App\Models\DangerType;
use Illuminate\Http\Request;

class DangerTypeController extends Controller
{
    public function index()
    {
        return view('danger-types.index');
    }

    public function store(Request $request)
    {
        $this->store_process($request);

        return redirect()->route('danger-types.index')->with('success', 'Danger Type created successfully.');
    }

    public function store_process($data)
    {
        $data->validate([
            'name' => 'required',
        ]);

        DangerType::create($data->all());
    }

    public function data()
    {
        $danger_types = DangerType::orderBy('name', 'asc')->get();

        return datatables()->of($danger_types)
            ->addIndexColumn()
            ->addColumn('action', 'danger-types.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
