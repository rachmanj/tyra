@extends('templates.main')

@section('title_page')
    Vendors
@endsection

@section('breadcrumb_title')
    Vendors
@endsection

@section('content')
<div class="row">
  <div class="col-12">

    <div class="card">
      <div class="card-header">
        <a href="{{ route('suppliers.create') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus"></i> New Vendor</a>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="supppliers" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th>#</th>
            <th>Nomor</th>
            <th>Vendor Name</th>
            <th>SAP Code</th>
            <th>Specifications</th>
            <th>Experience</th>
            <th>Status</th>
          </tr>
          </thead>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->


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
    $("#supppliers").DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('suppliers.data') }}',
      columns: [
        {data: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'reg_no'},
        {data: 'name'},
        {data: 'sap_code'},
        {data: 'specifications'},
        {data: 'experience'},
        {data: 'status'},
        // {data: 'action', orderable: false, searchable: false},
      ],
      fixedHeader: true,
      columnDefs: [
              {
                "targets": [5],
                "className": "text-right"
              },
            ]
    })
  });
</script>
@endsection