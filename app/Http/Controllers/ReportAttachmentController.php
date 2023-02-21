<?php

namespace App\Http\Controllers;

use App\Models\ReportAttachment;
use Illuminate\Http\Request;

class ReportAttachmentController extends Controller
{
    public function store($data)
    {
        $data->validate([
            'hazard_report_id' => 'required',
            'file_upload' => 'required',
        ]);

        $file = $data->file('file_upload');
        $filename = rand() . '_' . $file->getClientOriginalName();
        $file->move(public_path('document_upload'), $filename);

        $attachment = new ReportAttachment();
        $attachment->hazard_report_id = $data->hazard_report_id;
        $attachment->filename = $filename;
        $attachment->uploaded_by = auth()->user()->id;
        $attachment->save();
    }
}
