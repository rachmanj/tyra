<?php

namespace App\Http\Controllers;

use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }
}
