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
        // make query that return count of records by status of every project_code
        $project_codes = \App\Models\HazardReport::select('project_code')->distinct()->get();
        $project_codes_count = [];
        foreach ($project_codes as $project_code) {
            $project_codes_count[$project_code->project_code] = \App\Models\HazardReport::selectRaw('status, count(*) as count')->where('project_code', $project_code->project_code)->groupBy('status')->get();
        }

        return $project_codes_count;
    }

    public function count_by_danger_type()
    {
        // group by danger_type_id and count replace danger_type_id with danger_type_name
        $danger_types = HazardReport::selectRaw('danger_type_id, count(*) as count')->groupBy('danger_type_id')->get();
        $danger_types->map(function ($item) {
            $item->danger_type_id = \App\Models\HazardReport::find($item->danger_type_id)->danger_type->name;
        });

        return $danger_types;
    }

    public function test()
    {
        return $this->count_by_danger_type();
    }
}
