<?php

namespace App\Http\Controllers;

use App\Models\HazardReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // hazard_report_danger_type by danger_type_id count
        $danger_types = DB::table('hazard_report_danger_type')->selectRaw('danger_type_id, count(*) as count')->groupBy('danger_type_id')->get();

        // replace danger_type_id with danger_type_name
        foreach ($danger_types as $danger_type) {
            $danger_type->danger_type_id = \App\Models\DangerType::find($danger_type->danger_type_id)->name;
        }

        return $danger_types;
    }

    public function test()
    {
        // hazard_report_danger_type by danger_type_id count
        $danger_types = DB::table('hazard_report_danger_type')->selectRaw('danger_type_id, count(*) as count')->groupBy('danger_type_id')->get();

        // replace danger_type_id with danger_type_name
        foreach ($danger_types as $danger_type) {
            $danger_type->danger_type_name = \App\Models\DangerType::find($danger_type->danger_type_id)->name;
        }

        return $danger_types;
    }
}
