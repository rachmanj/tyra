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
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Tyre Detail</h3>
                <a href="{{ route('tyres.index') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-10">
                  @include('tyres.show_info')
                </div>
                <div class="col-2">
                  <h5>Total HM : </h5><h3> <strong>{{ $current_hm }}</strong></h3>
                </div>
              </div>
            </div>

            {{-- INSTALL REMOVE BUTTONS --}}
            <div class="card-footer">
              {{-- if tyre has no transactions or if the transaction type is OFF --}}
              @if ($tyre->transactions->count() < 1 || ($tyre->transactions->count() > 0 && $last_transaction->tx_type == 'OFF'))
              <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#tyre_install">Install Tyre</button>
              @else
              <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#tyre_install" disabled>Install Tyre</button>
              @endif
              @if ($tyre->transactions->count() > 0 && $last_transaction->tx_type == 'ON')
              <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tyre_remove">Remove Tyre</button>
              @else
              <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tyre_remove" disabled>Remove Tyre</button>
              @endif
            </div>

            {{-- HISTORIES --}}
            @include('tyres.show_histories')

        </div>
    </div>
</div>

{{-- MODAL --}}
@include('tyres.install_create')
@include('tyres.remove_create')

@endsection

@section('styles')
    <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/datatables/css/datatables.min.css') }}"/>
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('scripts')
    <!-- DataTables  & Plugins -->
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables/datatables.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
  
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })
</script>

<script>
  $(function () {
    $("#table-histories").DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('tyres.histories.data', $tyre->id) }}',
      columns: [
        {data: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'date'},
        {data: 'unit_no'},
        {data: 'tx_type'},
        {data: 'position'},
        {data: 'hm'},
        {data: 'rtd1'},
        {data: 'rtd2'},
        {data: 'remark'},
        {data: 'action_button', orderable: false, searchable: false},
      ],
      fixedHeader: true,
      columnDefs: [
              {
                "targets": [4, 5, 6, 7],
                "className": "text-right"
              },
        ]
    })
  });
</script>
@endsection