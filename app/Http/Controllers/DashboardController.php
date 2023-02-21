<?php

namespace App\Http\Controllers;

use App\Models\HazardReport;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $hazard_by_project = $this->getCountByProjects();
        $danger_types = $this->count_by_danger_type();

        return view('dashboard.index', compact('hazard_by_project', 'danger_types'));
    }

    public function getCountByProjects()
    {
        $projects = HazardReport::select('project_code')->distinct()->get()->pluck('project_code')->toArray();
        $statuses = ['pending', 'closed'];
        $project_codes_count = [];
        foreach ($projects as $project) {
            foreach ($statuses as $status) {
                $project_codes_count[$project][$status] = \App\Models\HazardReport::where('project_code', $project)->where('status', $status)->count();
            }
        }

        return $project_codes_count;
    }

    public function count_by_danger_type()
    {
        // group by danger_type_id and count replace danger_type_id with danger_type_name
        $danger_types = HazardReport::selectRaw('danger_type_id, count(*) as count')->groupBy('danger_type_id')->get();
        $danger_types->map(function ($item) {
            $item->danger_type_id = \App\Models\DangerType::find($item->danger_type_id)->name;
        });

        return $danger_types;
    }

    public function test()
    {
        $projects = HazardReport::select('project_code')->distinct()->get()->pluck('project_code')->toArray();
        $statuses = ['pending', 'closed'];
        $project_codes_count = [];
        foreach ($projects as $project) {
            foreach ($statuses as $status) {
                $project_codes_count[$project][$status] = \App\Models\HazardReport::where('project_code', $project)->where('status', $status)->count();
            }
        }

        return $project_codes_count;
    }
}
