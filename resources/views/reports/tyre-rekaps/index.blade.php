@extends('templates.main')

@section('title_page')
  Tyres  
@endsection

@section('breadcrumb_title')
    tyres
@endsection

@section('content')
<div class="row">
  <div class="col-12">

    <div class="card">
      <div class="card-header">
        <a href="{{ route('reports.tyre-rekaps.export') }}" class="btn btn-sm btn-success"><i class="fas fa-export"></i> Export</a>
        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-arrow-left"></i> Back</a>
      </div>  <!-- /.card-header -->
     
      <div class="card-body">
        <table id="tyres-table" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">SN</th>
            <th rowspan="2">Brand</th>
            <th rowspan="2">Vendor</th>
            <th rowspan="2">Price</th>
            <th colspan="2" class="text-center">Target</th>
            <th colspan="2" class="text-center">Realization</th>
            <th></th>
          </tr>
          <tr>
            <th>HM</th>
            <th>CPH</th>
            <th>HM</th>
            <th>CPH</th>
            <th></th>
          </tr>
          </thead>
        </table>
      </div> <!-- /.card-body -->
    </div> <!-- /.card -->
  </div> <!-- /.col -->
</div>  <!-- /.row -->


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
    $("#tyres-table").DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('reports.tyre-rekaps.data') }}',
      columns: [
        {data: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'serial_number'},
        {data: 'brand'},
        {data: 'vendor'},
        {data: 'price'},
        {data: 'hm_target'},
        {data: 'cph_target'},
        {data: 'hm_real'},
        {data: 'cph_real'},
        {data: 'action', orderable: false, searchable: false},
      ],
      fixedHeader: true,
      columnDefs: [
              {
                "targets": [4, 5, 6, 7, 8],
                "className": "text-right"
              },
        ]
    })
  });
</script>
@endsection