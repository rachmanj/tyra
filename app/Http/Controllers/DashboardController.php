<?php

namespace App\Http\Controllers;

use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        $vendors = Supplier::all();

        return view('dashboard.index', compact('vendors'));
    }
}
