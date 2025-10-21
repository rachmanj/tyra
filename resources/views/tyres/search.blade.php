@extends('templates.main')

@section('title_page')
    Tyre Search
@endsection

@section('breadcrumb_title')
    tyre search
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <x-tyre-links page="search" />

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Search Tyres</h3>
                </div>

                <div class="card-body">
                    <form id="search-form" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="serial_number">Serial Number</label>
                                    <input type="text" class="form-control" id="serial_number" name="serial_number">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="brand">Brand</label>
                                    <select class="form-control select2bs4" id="brand" name="brand">
                                        <option value="">-- Select Brand --</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pattern">Pattern</label>
                                    <select class="form-control select2bs4" id="pattern" name="pattern">
                                        <option value="">-- Select Pattern --</option>
                                        @foreach ($patterns as $pattern)
                                            <option value="{{ $pattern->id }}">{{ $pattern->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="project">Project</label>
                                    <select class="form-control select2bs4" id="project" name="project">
                                        <option value="">-- Select Project --</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project['project_code'] }}">{{ $project['project_code'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="po_no">PO Number</label>
                                    <input type="text" class="form-control" id="po_no" name="po_no">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="supplier">Supplier</label>
                                    <select class="form-control select2bs4" id="supplier" name="supplier">
                                        <option value="">-- Select Supplier --</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control select2bs4" id="status" name="status">
                                        <option value="">-- Select Status --</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inactive_reason">Inactive Reason</label>
                                    <select class="form-control select2bs4" id="inactive_reason" name="inactive_reason"
                                        disabled>
                                        <option value="">-- Select Inactive Reason --</option>
                                        <option value="Scrap">Scrap</option>
                                        <option value="Breakdown">Breakdown</option>
                                        <option value="Repair">Repair</option>
                                        <option value="Consignment">Consignment</option>
                                        <option value="Rotable">Rotable</option>
                                        <option value="Spare">Spare</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                <button type="reset" class="btn btn-sm btn-secondary">Reset</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered table-striped" id="search-results-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>SN</th>
                                <th>Brand</th>
                                <th>Pattern</th>
                                <th>Location</th>
                                <th>Vendor</th>
                                <th>PO No</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="search-results">
                            <!-- Results will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/datatables/css/datatables.min.css') }}" />
    {{-- select2bs4 --}}
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.css') }}">
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
    {{-- select2bs4 --}}
    <script src="{{ asset('adminlte/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(function() {
            // Check for success message from redirect
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 1500
                });
            @endif

            // Initialize select2
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Handle status change to enable/disable inactive reason
            $('#status').on('select2:select select2:unselect', function(e) {
                var statusValue = $(this).val();
                var inactiveReasonSelect = $('#inactive_reason');

                if (statusValue === '0') { // Inactive
                    inactiveReasonSelect.prop('disabled', false);
                    inactiveReasonSelect.select2('enable');
                } else {
                    inactiveReasonSelect.prop('disabled', true);
                    inactiveReasonSelect.val('').trigger('change'); // Clear selection
                    inactiveReasonSelect.select2('disable');
                }
            });

            // Also handle regular change event for fallback
            $('#status').on('change', function(e) {
                if (!e.originalEvent) return; // Skip programmatic changes

                var statusValue = $(this).val();
                var inactiveReasonSelect = $('#inactive_reason');

                if (statusValue === '0') { // Inactive
                    inactiveReasonSelect.prop('disabled', false);
                    inactiveReasonSelect.select2('enable');
                } else {
                    inactiveReasonSelect.prop('disabled', true);
                    inactiveReasonSelect.val('').trigger('change'); // Clear selection
                    inactiveReasonSelect.select2('disable');
                }
            });

            // Initialize DataTable with deferLoading
            var table = $("#search-results-table").DataTable({
                processing: true,
                serverSide: true,
                deferLoading: false, // Prevents initial ajax request
                ajax: {
                    url: '{{ route('tyres.search.data') }}',
                    data: function(d) {
                        d.serial_number = $('#serial_number').val();
                        d.brand = $('#brand').val();
                        d.pattern = $('#pattern').val();
                        d.supplier = $('#supplier').val();
                        d.po_no = $('#po_no').val();
                        d.project = $('#project').val();
                        d.status = $('#status').val();
                        d.inactive_reason = $('#inactive_reason').val();
                    }
                },
                columnDefs: [{
                        "targets": 4, // Location column
                        "width": "60px",
                        "className": "text-center"
                    },
                    {
                        "targets": 5, // Supplier column
                        "width": "200px"
                    }
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'serial_number',
                        name: 'serial_number'
                    },
                    {
                        data: 'brand_name',
                        name: 'brand_name'
                    },
                    {
                        data: 'pattern_name',
                        name: 'pattern_name'
                    },
                    {
                        data: 'current_project',
                        name: 'current_project',
                        className: 'text-center'
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier_name'
                    },
                    {
                        data: 'po_no',
                        name: 'po_no'
                    },
                    {
                        data: 'price',
                        name: 'price',
                        className: 'text-right'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'asc']
                ]
            });

            // Show message in table body initially
            $('#search-results-table tbody').html(
                '<tr><td colspan="10" class="text-center">Please click search to view data</td></tr>');

            // Handle search form submission
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                table.draw();
            });

            // Handle reset button
            $('#search-form button[type="reset"]').click(function(e) {
                e.preventDefault();

                // Clear all form inputs
                $(this).closest('form').find("input[type=text]").val("");

                // Reset the select2 elements one by one
                $('#project').val(null).trigger('change');
                $('#brand').val(null).trigger('change');
                $('#pattern').val(null).trigger('change');
                $('#supplier').val(null).trigger('change');
                $('#status').val(null).trigger('change');
                $('#inactive_reason').val(null).trigger('change');

                // Disable inactive reason after reset
                $('#inactive_reason').prop('disabled', true).select2('disable');

                // Clear the table and show initial message
                $('#search-results-table tbody').html(
                    '<tr><td colspan="10" class="text-center">Please click search to view data</td></tr>'
                );
            });
        });
    </script>
@endsection
