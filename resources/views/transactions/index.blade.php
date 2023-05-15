@extends('templates.main')

@section('title_page')
  Transactions  
@endsection

@section('breadcrumb_title')
    tx
@endsection

@section('content')
<div class="row">
  <div class="col-12">

    <div class="card">
      <div class="card-header">
        {{-- <a href="{{ route('tyres.create') }}" class="btn btn-sm btn-primary" ><i class="fas fa-plus"></i> Tyre</a> --}}
      </div>  <!-- /.card-header -->
     
      <div class="card-body">
        <table id="tyres-table" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th>#</th>
            <th>SN</th>
            <th>Date</th>
            <th>Unit No</th>
            <th>Tx Type</th>
            <th>Pos</th>
            <th>HM</th>
            <th>RTD1 | RTD2</th>
            <th>Reason</th>
            <th>Created By</th>
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
      ajax: '{{ route('transactions.data') }}',
      columns: [
        {data: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'tyre_sn'},
        {data: 'date'},
        {data: 'unit_no'},
        {data: 'tx_type'},
        {data: 'position'},
        {data: 'hm'},
        {data: 'rtd'},
        {data: 'removal_reason'},
        {data: 'created_by'},
        // {data: 'action', orderable: false, searchable: false},
      ],
      fixedHeader: true,
      columnDefs: [
              {
                "targets": [5, 6],
                "className": "text-right"
              },
              {
                "targets": [5, 7],
                "className": "text-center"
              },
        ]
    })
  });
</script>
@endsection