@extends('templates.main')

@section('title_page')
  Hazard Report
@endsection

@section('breadcrumb_title')
    hazard-report
@endsection

@section('content')
    <div class="row">
      <div class="col-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title"> Hazard Report Detail</h3>
            <a href="{{ route('hazard-rpt.index') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-undo"></i> Back</a>
            <form action={{ route('hazard-rpt.close_report', $hazard->id) }} metho='POST' id="close-report">
              @csrf
              @method('PUT')
              <button type="submit" class="btn btn-sm btn-default float-right mr-5" onclick="return confirm('Are you sure to close this record?')"> <b>CLOSE Report</b></button>
            </form>
          </div>
          <div class="card-body">
            <dl class="row">
              <dt class="col-sm-4">Report No</dt>
              <dd class="col-sm-8">: <b>{{ $hazard->nomor }}</b></dd>
              <dt class="col-sm-4">Date & Time</dt>
              <dd class="col-sm-8">: {{ $hazard->created_at->addHours(8)->format('d M Y - H:i:s') }} </dd>
              <dt class="col-sm-4">Project</dt>
              <dd class="col-sm-8">: {{ $hazard->project_code }}</dd>
              <dt class="col-sm-4">To Department</dt>
              <dd class="col-sm-8">: {{ $hazard->department->department_name }}</dd>
              <dt class="col-sm-4">Category</dt>
              <dd class="col-sm-8">: {{ $hazard->category }}</dd>
              <dt class="col-sm-4">Danger Type</dt>
              <dd class="col-sm-8">: {{ $hazard->danger_type->name }}</dd>
              <dt class="col-sm-4">Created by | Department</dt>
              <dd class="col-sm-8">: {{ $hazard->employee->name }} | {{ $hazard->employee->department->department_name }}</dd>
              <dt class="col-sm-4">Description</dt>
              <dd class="col-sm-8">: {{ $hazard->description }}</dd>
            </dl>
          </div>
          <div class="card-header">
            <h3 class="card-title"> Hazard Report Attachment</h3>
            <button data-toggle="modal" data-target="#modal-attachment" class="btn btn-sm btn-default float-right"> Add Attachment</button>
          </div>
          <div class="card-body">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>File Name</th>
                  <th>Uploaded By</th>
                  <th>Uploaded At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($attachments as $attachment)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $attachment->filename }}</td>
                    <td>{{ $attachment->uploadedBy->name }}</td>
                    <td>{{ $attachment->created_at->addHours(8)->format('d M Y - H:i:s') }}</td>
                    <td>
                      <a href="{{ asset('document_upload') . '/' . $attachment->filename }}" class='btn btn-xs btn-info' target=_blank>view</a>
                      <a href="{{ route('report-attachment.destroy', $attachment->id) }}" class="btn btn-xs btn-danger"> delete</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="card-header">
            <h3 class="card-title"> Hazard Responses</h3>
            <button data-toggle="modal" data-target="#modal-response" class="btn btn-sm btn-default float-right"> Add Response</button>
          </div>
          <div class="card-body">
            <table id="response-table" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Response At</th>
                  <th>Response By</th>
                  <th>Comment</th>
                  <th>attachment</th>
                  <th></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>



    {{-- modal-attachment --}}
    <div class="modal fade" id="modal-attachment">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Upload File</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{ route('hazard-rpt.store_attachment') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
              <input type="hidden" name="hazard_report_id" value="{{ $hazard->id }}">
              <div class="form-group">
                <label for="file_upload">Upload Attachment</label>
                <input type="file" name="file_upload" id="file_upload" class="form-control">
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Submit</button>
            </div>
          </form>
        </div> <!-- /.modal-content -->
      </div> <!-- /.modal-dialog -->
    </div> <!-- /.modal-attachment -->

    {{-- modal-response --}}
    <div class="modal fade" id="modal-response">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Create Response</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{ route('hazard-rpt.store_response') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
              <input type="hidden" name="hazard_report_id" value="{{ $hazard->id }}">
              <div class="form-group">
                <label for="comment">Comment</label>
                <textarea name="comment" id="comment" class="form-control" cols="30" rows="3">{{ old('comment') }}</textarea>
              </div>
              <div class="form-group">
                <label for="file_upload">Upload Attachment <small>(optional)</small></label>
                <input type="file" name="file_upload" id="file_upload" class="form-control">
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Submit</button>
            </div>
          </form>
        </div> <!-- /.modal-content -->
      </div> <!-- /.modal-dialog -->
    </div> <!-- /.modal-attachment -->


@endsection

@section('styles')
    <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/datatables/css/datatables.min.css') }}"/>
@endsection

@section('scripts')
    <!-- DataTables  & Plugins -->
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables/datatables.min.js') }}"></script>

<script>
  $(function () {
    $("#response-table").DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('hazard-rpt.response_data', $hazard->id) }}',
      columns: [
        {data: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'created_at'},
        {data: 'comment_by'},
        {data: 'comment'},
        {data: 'attachment'},
        {data: 'action'},
      ],
      fixedHeader: true,
      lengthChange: false,
      searching: false,
    })
  });
</script>
@endsection 