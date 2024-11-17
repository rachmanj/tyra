<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class TestController extends Controller
{
    public function index()
    {
        $test = app(DashboardController::class)->generate_rekap_data_by_brand();

        return $test;
    }
}
