@extends('templates.main')

@section('title_page')
    Announcements
@endsection

@section('breadcrumb_title')
    announcements
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <a href="{{ route('announcements.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add Announcement
                    </a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="announcements_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="align-middle">No</th>
                                <th class="align-middle">Content</th>
                                <th class="align-middle">Start Date</th>
                                <th class="align-middle">Duration (Days)</th>
                                <th class="align-middle">End Date</th>
                                <th class="align-middle">Status</th>
                                <th class="align-middle">Created By</th>
                                <th class="align-middle text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($announcements as $index => $announcement)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div
                                            style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ Str::limit($announcement->content, 100) }}
                                        </div>
                                    </td>
                                    <td>{{ $announcement->start_date->format('d/m/Y') }}</td>
                                    <td>{{ $announcement->duration_days }} days</td>
                                    <td>{{ $announcement->end_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if ($announcement->status === 'active')
                                            @if ($announcement->is_current)
                                                <span class="badge badge-success">Active & Current</span>
                                            @elseif($announcement->is_expired)
                                                <span class="badge badge-warning">Active but Expired</span>
                                            @else
                                                <span class="badge badge-info">Active (Future)</span>
                                            @endif
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $announcement->creator->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('announcements.show', $announcement) }}"
                                                class="btn btn-sm btn-info mr-2" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('announcements.edit', $announcement) }}"
                                                class="btn btn-sm btn-warning mr-2" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('announcements.toggle_status', $announcement) }}"
                                                method="POST" style="display: inline;" class="mr-2">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="btn btn-sm {{ $announcement->status === 'active' ? 'btn-secondary' : 'btn-success' }}"
                                                    title="{{ $announcement->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                    <i
                                                        class="fas {{ $announcement->status === 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('announcements.destroy', $announcement) }}"
                                                method="POST" style="display: inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
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
@endsection

@section('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(function() {
            $("#announcements_table").DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                order: [
                    [2, 'desc']
                ], // Sort by start_date descending
                columnDefs: [{
                        orderable: false,
                        targets: [7]
                    } // Disable sorting on Actions column
                ]
            });
        });
    </script>
@endsection
