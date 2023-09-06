@extends('templates.main')

@section('title_page')
  Tyre Sizes  
@endsection

@section('breadcrumb_title')
    tyre-sizes
@endsection

@section('content')
<div class="row">
  <div class="col-12">

    <div class="card">
      <div class="card-header">
        <button href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-create"><i class="fas fa-plus"></i> Tyre Size</button>
      </div>  <!-- /.card-header -->
     
      <div class="card-body">
        <table id="tyresize-table" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th>#</th>
            <th>Description</th>
            <th class="text-right">AVG CPH</th>
            <th></th>
          </tr>
          </thead>
        </table>
      </div> <!-- /.card-body -->
    </div> <!-- /.card -->
  </div> <!-- /.col -->
</div>  <!-- /.row -->

{{-- Modal create --}}
<div class="modal fade" id="modal-create">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"> New Tyre Size</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('tyre-sizes.store') }}" method="POST">
        @csrf
      <div class="modal-body">

        <div class="form-group">
          <label for="description">Description</label>
          <input name="description" id="description" class="form-control @error('description') is-invalid @enderror" autofocus>
          @error('description')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

      </div> <!-- /.modal-body -->
      <div class="modal-footer float-left">
        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"> Close</button>
        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Save</button>
      </div>
    </form>
    </div> <!-- /.modal-content -->
  </div> <!-- /.modal-dialog -->
</div>
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
    $("#tyresize-table").DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('tyre-sizes.data') }}',
      columns: [
        {data: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'description'},
        {data: 'avg_cph'},
        {data: 'action', orderable: false, searchable: false},
      ],
      fixedHeader: true,
      columnDefs: [
              {
                "targets": [2],
                "className": "text-right"
              },
        ]
    })
  });
</script>
@endsection