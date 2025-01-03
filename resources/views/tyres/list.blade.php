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

            <x-tyre-links page="list" />

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tyres</h3>
                </div> <!-- /.card-header -->

                <div class="card-body">
                    <table id="tyres-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>SN</th>
                                <th>Unit No</th>
                                <th>Brand</th>
                                <th>Location</th>
                                <th>Vendor</th>
                                <th>Price</th>
                                <th>Target_H <br> Warranty_D <br> Warranty_H</th>
                                <th>Target CPH</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
@endsection

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/datatables/css/datatables.min.css') }}" />
    <style>
        .card-header .active {
            color: black;
            text-transform: uppercase;
        }
    </style>
@endsection

@section('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables/datatables.min.js') }}"></script>

    <script>
        $(function() {
            $("#tyres-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('tyres.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'serial_number'
                    },
                    {
                        data: 'unit_no'
                    },
                    {
                        data: 'brand'
                    },
                    {
                        data: 'current_project'
                    },
                    {
                        data: 'vendor'
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'hours_target'
                    },
                    {
                        data: 'cph'
                    },
                    {
                        data: 'is_active'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                fixedHeader: true,
                columnDefs: [{
                    "targets": [6, 7, 8],
                    "className": "text-right"
                }, ]
            })
        });
    </script>
@endsection
