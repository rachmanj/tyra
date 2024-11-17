<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Tyre;
use App\Models\TyreBrand;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'active_tyres' => Tyre::where('is_active', 1)->get(),
            'avg_active' => $this->avgActive(),
            'avg_inactive' => $this->avgInActive(),
            'active_tyre_by_project' => $this->activeTyreByProject(),
            'data' => $this->generate_rekap_data(),
            'by_brands' => $this->generate_rekap_data_by_brand(),
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

    public function generate_rekap_data()
    {
        $projects = ['017C', '021C', '022C', '023C'];
        $data = [];

        $total_active_hm = 0;
        $total_active_price = 0;
        $total_inactive_hm = 0;
        $total_inactive_price = 0;

        foreach ($projects as $project) {
            $tyres = Tyre::where('current_project', $project)->get();

            $total_hm = $tyres->sum('accumulated_hm');
            $total_price = $tyres->sum('price');
            $average_cph = $total_hm && $total_price ? number_format($total_price / $total_hm, 2) : '-';

            $active_tyres = Tyre::where('is_active', 1)
                ->where('current_project', $project)
                ->get();
            $inactive_tyres = Tyre::where('is_active', 0)
                ->where('current_project', $project)
                ->get();

            $active_total_hm = $active_tyres->sum('accumulated_hm');
            $active_total_price = $active_tyres->sum('price');
            $active_average_cph = $active_total_hm && $active_total_price ? number_format($active_total_price / $active_total_hm, 2) : '-';

            $inactive_total_hm = $inactive_tyres->sum('accumulated_hm');
            $inactive_total_price = $inactive_tyres->sum('price');
            $inactive_average_cph = $inactive_total_hm && $inactive_total_price ? number_format($inactive_total_price / $inactive_total_hm, 2) : '-';

            $total_active_hm += $active_total_hm;
            $total_active_price += $active_total_price;
            $total_inactive_hm += $inactive_total_hm;
            $total_inactive_price += $inactive_total_price;

            $data[] = [
                'project' => $project,
                'active_tyres' => $active_tyres->count(),
                'inactive_tyres' => $inactive_tyres->count(),
                'average_cph' => $average_cph,
                'active_average_cph' => $active_average_cph,
                'inactive_average_cph' => $inactive_average_cph,
            ];
        }

        $total_active_average_cph = $total_active_hm && $total_active_price ? number_format($total_active_price / $total_active_hm, 2) : '-';
        $total_inactive_average_cph = $total_inactive_hm && $total_inactive_price ? number_format($total_inactive_price / $total_inactive_hm, 2) : '-';

        return [
            'data' => $data,
            'total_active' => [
                'total_active_hm' => $total_active_hm,
                'total_active_price' => $total_active_price,
                'total_active_average_cph' => $total_active_average_cph,
            ],
            'total_inactive' => [
                'total_inactive_hm' => $total_inactive_hm,
                'total_inactive_price' => $total_inactive_price,
                'total_inactive_average_cph' => $total_inactive_average_cph,
            ],
        ];
    }

    public function generate_rekap_data_by_brand()
    {
        $brands = TyreBrand::all();
        $data = [];

        foreach ($brands as $brand) {
            $tyres = Tyre::where('brand_id', $brand->id)->get();

            $total_hm = $tyres->sum('accumulated_hm');
            $total_price = $tyres->sum('price');
            $average_cph = $total_hm && $total_price ? number_format($total_price / $total_hm, 2) : '-';

            $active_tyres = Tyre::where('is_active', 1)
                ->where('brand_id', $brand->id)
                ->get();
            $inactive_tyres = Tyre::where('is_active', 0)
                ->where('brand_id', $brand->id)
                ->get();

            $active_total_hm = $active_tyres->sum('accumulated_hm');
            $active_total_price = $active_tyres->sum('price');
            $active_average_cph = $active_total_hm && $active_total_price ? number_format($active_total_price / $active_total_hm, 2) : '-';

            $inactive_total_hm = $inactive_tyres->sum('accumulated_hm');
            $inactive_total_price = $inactive_tyres->sum('price');
            $inactive_average_cph = $inactive_total_hm && $inactive_total_price ? number_format($inactive_total_price / $inactive_total_hm, 2) : '-';

            $data[] = [
                'brand' => $brand->name,
                'active_tyres' => $active_tyres->count(),
                'inactive_tyres' => $inactive_tyres->count(),
                'average_cph' => $average_cph,
                'active_average_cph' => $active_average_cph,
                'inactive_average_cph' => $inactive_average_cph,
            ];
        }

        return $data;
    }
}
