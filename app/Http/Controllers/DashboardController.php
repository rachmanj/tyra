<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Tyre;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'active_tyres' => Tyre::where('is_active', 1)->get(),
            'avg_active' => $this->avgActive(),
            'avg_inactive' => $this->avgInActive(),
            'active_tyre_by_project' => $this->activeTyreByProject(),
        ]);
    }

    public function avgActive()
    {
        $tyres = Tyre::where('is_active', 1)->get();

        $total_hm = $tyres->sum('accumulated_hm');
        $total_price = $tyres->sum('price');

        $average_cph = $total_hm && $total_price ? number_format($total_price / $total_hm, 2) : '-';

        return $average_cph;
    }

    public function avgInActive()
    {
        $tyres = Tyre::where('is_active', 0)->get();

        $total_hm = $tyres->sum('accumulated_hm');
        $total_price = $tyres->sum('price');

        $average_cph = $total_hm && $total_price ? number_format($total_price / $total_hm, 2) : '-';

        return $average_cph;
    }

    public function activeTyreByProject()
    {
        $active_tyres = Tyre::where('is_active', 1)
            ->selectRaw('count(*) as total, current_project')
            ->groupBy('current_project')
            ->get();

        return $active_tyres;
    }
}
