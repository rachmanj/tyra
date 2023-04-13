@extends('templates.main')

@section('title_page')
  Migrations  
@endsection

@section('breadcrumb_title')
    migrations
@endsection

@section('content')
<div class="row">
  <div class="col-12">

    <div class="card">
      <div class="card-header">
        <a href="{{ route('tyres.migration') }}" class="btn btn-sm btn-warning">Migrate</a>
        <button href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-create"><i class="fas fa-plus"></i> Tyre</button>
      </div>  <!-- /.card-header -->
     
      <div class="card-body">
        <table id="tyres-table" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th>#</th>
            <th>Size ID</th>
            <th>TyreSize</th>
            <th>Brand ID</th>
            <th>ManufacName</th>
            <th>Pattern ID</th>
            <th>TyrePattern</th>
            <th>Vendor ID</th>
            <th>TyreVendor</th>
            {{-- <th></th> --}}
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
        <h4 class="modal-title"> New Tyre</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('tyres.store') }}" method="POST">
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
    $("#tyres-table").DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('tyres.data') }}',
      columns: [
        {data: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'size_id'},
        {data: 'TyreSize'},
        {data: 'brand_id'},
        {data: 'TyreManufName'},
        {data: 'pattern_id'},
        {data: 'TyrePattern'},
        {data: 'supplier_id'},
        {data: 'TyreVendor'},
        // {data: 'action', orderable: false, searchable: false},
      ],
      fixedHeader: true,
    })
  });
</script>
@endsection