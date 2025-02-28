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
                    <a href="{{ route('tyres.index', ['page' => 'search']) }}" class="btn btn-sm btn-primary float-right"><i
                            class="fas fa-arrow-left"></i> Back</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            @include('tyres.show_info')
                        </div>
                        <div class="col-4">
                            <h5>Total HM : </h5>
                            <h3> <strong
                                    id="accumulated-hm">{{ number_format($tyre->accumulated_hm, 0, ',', '.') }}</strong>
                            </h3>

                            <h5 class="mt-3">This Tyre CPH : </h5>
                            <h3> <strong
                                    id="tyre-cph">{{ $tyre->accumulated_hm > 0 ? number_format($tyre->price / $tyre->accumulated_hm, 2, ',', '.') : 'N/A' }}</strong>
                            </h3>

                            <h5 class="mt-3">This Brand Avg CPH : </h5>
                            <h3> <strong id="avg-cph">Loading...</strong></h3>

                            @can('update_last_hm')
                                <div class="form-group mt-3">
                                    <label for="last_hm">Update Tyre HM</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="last_hm" name="last_hm" min="0"
                                            autocomplete="off" placeholder="Enter new HM value">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-sm btn-primary" id="update-hm"
                                                data-tyre-id="{{ $tyre->id }}"
                                                data-brand-id="{{ $tyre->brand_id }}">Update</button>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>

                {{-- INSTALL REMOVE BUTTONS --}}
                <div class="card-footer">
                    @if ($tyre->is_active == 1)
                        {{-- if tyre has no transactions or if the transaction type is OFF --}}
                        @if ($tyre->transactions->count() < 1 || ($tyre->transactions->count() > 0 && $last_transaction->tx_type == 'OFF'))
                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#tyre_install">Install
                                Tyre</button>
                        @else
                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#tyre_install"
                                disabled>Install Tyre</button>
                        @endif
                        @if (
                            ($tyre->transactions->count() > 0 && $last_transaction->tx_type == 'ON') ||
                                ($tyre->transactions->count() > 0 && $last_transaction->tx_type == 'UHM'))
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tyre_remove">Remove
                                Tyre</button>
                        @else
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tyre_remove"
                                disabled>Remove Tyre</button>
                        @endif

                        {{-- IN-ACTIVATE BUTTON --}}

                        {{-- if tyre has no transactions or if the transaction type is OFF --}}
                        @can('tyre_activation')
                            @if ($tyre->transactions->count() < 1 || ($last_transaction && $last_transaction->tx_type == 'OFF'))
                                <a href="{{ route('tyres.activate', $tyre->id) }}"
                                    class="btn btn-sm btn-warning float-right">In-Activate Tyre</a>
                            @else
                                <a href="{{ route('tyres.activate', $tyre->id) }}" class="btn btn-sm btn-warning float-right"
                                    disabled>In-Activate Tyre</a>
                            @endif
                        @endcan
                    @elseif ($tyre->is_active == 0)
                        @can('tyre_activation')
                            <a href="{{ route('tyres.activate', $tyre->id) }}"
                                class="btn btn-sm btn-warning float-right">Activate Tyre</a>
                        @endcan
                    @endif
                    {{-- RESET HM BUTTON --}}
                    @if ($tyre->accumulated_hm !== 0)
                        @can('reset_hm')
                            <form action="{{ route('tyres.reset_hm', $tyre->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-sm btn-danger float-right mt-2"
                                    onclick="return confirm('Are you sure you want to reset HM?')">RESET HM</button>
                            </form>
                        @endcan
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
    <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/datatables/css/datatables.min.css') }}" />
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
        $(document).ready(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            // get equipments list using ajax
            let url = "{{ env('URL_ARKFLEET') }}/equipments"

            $.get(url, function(data, status) {
                let equipments = data.data
                //  get value of project_equipment from controller 
                let project_equipment = "{{ $project_equipment }}"

                let filtered_equipments = []
                if (project_equipment == 'all') {
                    filtered_equipments = equipments
                } else {
                    filtered_equipments = equipments.filter(equipment => equipment.project ==
                        project_equipment)
                }

                let select = document.getElementById('unit_no')

                for (let i = 0; i < filtered_equipments.length; i++) {
                    let equipment = filtered_equipments[i]
                    let option = document.createElement('option')
                    option.value = equipment.unit_code
                    option.text = equipment.unit_code + ' - ' + equipment.plant_group + ' - ' + equipment
                        .model
                    select.add(option)
                }

                // let selected equipment
                let unit_no = "{{ old('unit_no') }}"

                if (unit_no) {
                    $('#unit_no').val(unit_no).trigger('change')
                }
            })

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            $("#table-histories").DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('tyres.histories.data', $tyre->id) }}',
                drawCallback: function() {
                    // Re-initialize tooltips after table redraw
                    $('[data-toggle="tooltip"]').tooltip();
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'unit_no'
                    },
                    {
                        data: 'tx_type'
                    },
                    {
                        data: 'position'
                    },
                    {
                        data: 'hm'
                    },
                    {
                        data: 'rtd1'
                    },
                    {
                        data: 'removal_reason'
                    },
                    {
                        data: 'action_button',
                        orderable: false,
                        searchable: false
                    },
                ],
                fixedHeader: true,
                columnDefs: [{
                        "targets": [4, 5],
                        "className": "text-right"
                    },
                    {
                        "targets": [6],
                        "className": "text-center"
                    },
                ]
            })

            // Add number formatter function for Indonesian format
            const formatNumber = (number, decimals = 0) => {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: decimals,
                    maximumFractionDigits: decimals
                }).format(number);
            };

            // Function to load and display Avg CPH
            function loadAvgCph(brandId) {
                $.ajax({
                    url: '{{ url('/') }}/tyres/' + brandId + '/avg-cph',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#avg-cph').text(formatNumber(response.avg_cph, 2));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading Avg CPH:', error);
                        $('#avg-cph').text('Error');
                    }
                });
            }

            // Load initial Avg CPH
            loadAvgCph({{ $tyre->brand_id }});

            // Update HM click handler
            $('#update-hm').click(function(e) {
                e.preventDefault();

                const tyreId = $(this).data('tyre-id');
                const lastHm = $('#last_hm').val();

                if (!lastHm) {
                    alert('Please enter HM value');
                    return;
                }

                // Get last transaction HM and validate
                $.ajax({
                    url: '{{ url('/tyres/get-last-hm') }}',
                    type: 'GET',
                    data: {
                        tyre_id: tyreId
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.success) {
                            const currentHm = response.current_hm;

                            if (parseInt(lastHm) < currentHm) {
                                alert('New HM cannot be less than last transaction HM (' +
                                    formatNumber(currentHm) + ')');
                                return;
                            }

                            // If validation passes, proceed with update
                            updateTyreHm(tyreId, lastHm);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert(xhr.responseJSON?.message || 'Error validating HM value');
                    }
                });
            });

            // Function to update tyre HM
            function updateTyreHm(tyreId, lastHm) {
                // Add confirmation dialog
                if (!confirm(
                        `Are you sure you want to update the HM to ${formatNumber(lastHm)}?`
                    )) {
                    return;
                }

                $.ajax({
                    url: '{{ url('/') }}/tyres/' + tyreId + '/update-hm',
                    type: 'POST',
                    data: {
                        last_hm: lastHm,
                        _token: '{{ csrf_token() }}'
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#accumulated-hm').text(formatNumber(response.accumulated_hm));
                            $('#tyre-cph').text(formatNumber(response.tyre_cph, 2));
                            $('#avg-cph').text(formatNumber(response.avg_cph, 2));
                            $('#last_hm').val('');
                            $('#table-histories').DataTable().ajax.reload();
                            alert('HM updated successfully');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            alert(xhr.responseJSON.message);
                        } else {
                            alert(xhr.responseJSON?.message || 'Error updating HM');
                        }
                    }
                });
            }
        });
    </script>

    {{-- <script>
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
</script> --}}
@endsection
