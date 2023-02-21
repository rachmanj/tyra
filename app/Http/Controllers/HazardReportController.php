<?php

namespace App\Http\Controllers;

use App\Models\DangerType;
use App\Models\Department;
use App\Models\HazardReport;
use App\Models\ReportAttachment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HazardReportController extends Controller
{
    public function index()
    {
        return view('hazard-rpt.index');
    }

    public function create()
    {
        $projects = ['000H', '001H', '017C', '021C', '022C', '023C', 'APS'];
        $departments = Department::orderBy('department_name', 'asc')->get();
        $danger_types = DangerType::orderBy('name', 'asc')->get();
        $date_time = Carbon::now()->addHours(8)->format('d M Y H:i:s');
        $nomor = 'H' . Carbon::now()->addHours(8)->format('y') . '-' . str_pad(HazardReport::count() + 1, 3, '0', STR_PAD_LEFT);

        return view('hazard-rpt.create', compact('projects', 'departments', 'danger_types', 'date_time', 'nomor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_code' => 'required',
            'to_department_id' => 'required',
            'danger_type_id' => 'required',
            'description' => 'required',
        ]);

        $hazard = new HazardReport();
        $hazard->nomor = 'H' . Carbon::now()->addHours(8)->format('y') . '/' . $request->project_code . '/' . str_pad(HazardReport::count() + 1, 3, '0', STR_PAD_LEFT);
        $hazard->project_code = $request->project_code;
        $hazard->to_department_id = $request->to_department_id;
        $hazard->category = $request->category;
        $hazard->danger_type_id = $request->danger_type_id;
        $hazard->description = $request->description;
        $hazard->created_by = auth()->user()->id;
        $hazard->save();

        return redirect()->route('hazard-rpt.index')->with('success', 'Hazard Report has been created successfully.');
    }

    public function show($id)
    {
        $hazard = HazardReport::findOrFail($id);
        $attachments = ReportAttachment::where('hazard_report_id', $id)->get();

        return view('hazard-rpt.show', compact('hazard', 'attachments'));
    }

    public function store_attachment(Request $request)
    {
        app(ReportAttachmentController::class)->store($request);

        return redirect()->route('hazard-rpt.show', $request->hazard_report_id)->with('success', 'Attachment has been uploaded successfully.');
    }

    public function store_response(Request $request)
    {
        app(HazardResponseController::class)->store($request);

        return redirect()->route('hazard-rpt.show', $request->hazard_report_id)->with('success', 'Response has been submitted successfully.');
    }

    public function edit($id)
    {
        $hazard = HazardReport::findOrFail($id);
        $projects = ['000H', '001H', '017C', '021C', '022C', '023C', 'APS'];
        $departments = Department::orderBy('department_name', 'asc')->get();
        $danger_types = DangerType::orderBy('name', 'asc')->get();

        return view('hazard-rpt.edit', compact('hazard', 'projects', 'departments', 'danger_types'));
    }

    public function close_report($id) // update status to closed
    {
        $hazard = HazardReport::findOrFail($id);
        $hazard->status = 'closed';
        $hazard->updated_by = auth()->user()->id;
        $hazard->closed_date = Carbon::now();
        $hazard->save();

        return redirect()->route('hazard-rpt.index')->with('success', 'Hazard Report has been closed successfully.');
    }

    public function destroy($id)
    {
        $hazard = HazardReport::findOrFail($id);
        $hazard->delete();

        return redirect()->route('hazard-rpt.index')->with('success', 'Hazard Report has been deleted successfully.');
    }

    public function closed_index()
    {
        return view('hazard-rpt.closed_index');
    }

    public function data()
    {
        $hazards = HazardReport::where('status', 'pending')->orderBy('created_at', 'desc')
            ->get();

        return datatables()->of($hazards)
            ->editColumn('created_at', function ($hazard) {
                // 8 hours added because of timezone
                return $hazard->created_at->addHours(8)->format('d M Y - H:i:s');
            })
            ->editColumn('updated_at', function ($hazard) {
                return $hazard->updated_at->addHours(8)->format('d M Y - H:i:s');
            })
            ->editColumn('to_department_id', function ($hazard) {
                return $hazard->department->department_name;
            })
            ->editColumn('description', function ($hazard) {
                return '<small>' . $hazard->description . '</small>';
            })
            ->addColumn('days', function ($hazard) {
                return $hazard->created_at->addHours(8)->diffInDays(Carbon::now()->addHours(8));
            })
            ->addColumn('action', 'hazard-rpt.action')
            ->addIndexColumn()
            ->rawColumns(['action', 'description'])
            ->toJson();
    }

    public function response_data($id)
    {
        $responses = app(HazardResponseController::class)->get_data($id);


        return datatables()->of($responses)
            ->editColumn('created_at', function ($response) {
                // 8 hours added because of timezone
                return $response->created_at->addHours(8)->format('d-m-Y - H:i:s');
            })
            ->editColumn('comment_by', function ($response) {
                return $response->employee->name;
            })
            ->editColumn('comment', function ($response) {
                return '<small>' . $response->comment . '</small>';
            })
            ->editColumn('attachment', function ($response) {
                if ($response->filename) {
                    return '<a href="' . asset('document_upload/' . $response->filename) . '" target="_blank" class="btn btn-xs btn-info"> view</a>';
                } else {
                    return ' - ';
                }
            })
            ->addColumn('action', 'hazard-rpt.response_action')
            ->addIndexColumn()
            ->rawColumns(['action', 'comment', 'attachment'])
            ->toJson();
        // ->make(true);
    }

    public function closed_data()
    {
        $hazards = HazardReport::where('status', 'closed')->orderBy('created_at', 'desc')
            ->get();

        return datatables()->of($hazards)
            ->editColumn('created_at', function ($hazard) {
                // 8 hours added because of timezone
                return $hazard->created_at->addHours(8)->format('d M Y - H:i:s');
            })
            ->editColumn('updated_at', function ($hazard) {
                return $hazard->updated_at->addHours(8)->format('d M Y - H:i:s');
            })
            ->editColumn('to_department_id', function ($hazard) {
                return $hazard->department->department_name;
            })
            ->editColumn('description', function ($hazard) {
                return '<small>' . $hazard->description . '</small>';
            })
            ->addColumn('duration', function ($hazard) {
                if ($hazard->created_at && $hazard->closed_date) {
                    $end_date = Carbon::createFromFormat('Y-m-d H:s:i', $hazard->closed_date);
                    $start_date = Carbon::createFromFormat('Y-m-d H:s:i', $hazard->created_at);
                    $days = $start_date->diffInDays($end_date);
                    $hours = $start_date->copy()->addDays($days)->diffInHours($end_date);
                    $minutes = $start_date->copy()->addDays($days)->addHours($hours)->diffInMinutes($end_date);
                    return $days . 'd ' . $hours . 'h ' . $minutes . 'm';
                } else {
                    return '-';
                }
            })
            ->addColumn('action', 'hazard-rpt.closed_action')
            ->addIndexColumn()
            ->rawColumns(['action', 'description'])
            ->toJson();
    }
}
