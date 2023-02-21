<?php

namespace App\Http\Controllers;

use App\Models\HazardResponse;
use Illuminate\Http\Request;

class HazardResponseController extends Controller
{
    public function store($data)
    {
        $data->validate([
            'hazard_report_id' => 'required',
            'comment' => 'required',
        ]);

        if ($data->hasFile('file_upload')) {
            $file = $data->file('file_upload');
            $filename = rand() . '_' . $file->getClientOriginalName();
            $file->move(public_path('document_upload'), $filename);
        } else {
            $filename = null;
        }

        $hazard_response = new HazardResponse();
        $hazard_response->hazard_report_id = $data->hazard_report_id;
        $hazard_response->comment_by = auth()->user()->id;
        $hazard_response->comment = $data->comment;
        $hazard_response->filename = $filename;
        $hazard_response->save();
    }

    public function destroy(Request $request, $id)
    {
        $hazard_response = HazardResponse::findOrFail($id);
        $hazard_response->delete();

        return redirect()->route('hazard-rpt.show', $request->hazard_report_id)->with('success', 'Hazard Response has been deleted successfully.');
    }

    public function get_data($hazard_report_id)
    {
        $hazard_responses = HazardResponse::where('hazard_report_id', $hazard_report_id)->get();

        return $hazard_responses;
    }
}
