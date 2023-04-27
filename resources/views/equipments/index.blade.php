@extends('templates.main')

@section('title_page')
  Equipments
@endsection

@section('breadcrumb_title')
    equipments
@endsection

@section('content')
<div class="row">
  <div class="col-12">

    <div class="card">
      <div class="card-header">
        <h3 class="card-title">This List use API call from ARKFleet</h3>
      </div>  <!-- /.card-header -->
     
      <div class="card-body">
        <table id="equipments-table" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th>#</th>
            <th>Unit No</th>
            <th>Project</th>
            <th>Plant Group</th>
            <th>Model</th>
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
{{-- axios --}}
<script src="{{ asset('adminlte/axios/axios.min.js') }}"></script>

<script>
   // call equipments api using axios
   let url = "{{ env('URL_ARKFLEET') }}/equipments";

axios.get(url)
.then(function (response) {
  // call datatable
  $('#equipments-table').DataTable({
    data: response.data.data,
    columns: [
      { data: null, render: function (data, type, row, meta) {
        return meta.row + meta.settings._iDisplayStart + 1;
      } },
      { data: 'unit_code' },
      { data: 'project' },
      { data: 'plant_group' },
      { data: 'model' },
    ]
  });
})
</script>
@endsection