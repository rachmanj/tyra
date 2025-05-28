@extends('templates.main')

@section('title_page')
    Edit Announcement
@endsection

@section('breadcrumb_title')
    announcements / edit
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Announcement Form</h3>
                    <div class="card-tools">
                        <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <form action="{{ route('announcements.update', $announcement) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">

                        <div class="form-group">
                            <label for="content">Announcement Content <span class="text-danger">*</span></label>
                            <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror"
                                placeholder="Enter announcement content..." autofocus>{{ old('content', $announcement->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Write announcement content that will be displayed on the dashboard. Maximum 65,535
                                characters.
                            </small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date"
                                        class="form-control @error('start_date') is-invalid @enderror"
                                        value="{{ old('start_date', $announcement->start_date->format('Y-m-d')) }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Start date when announcement will be displayed.
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration_days">Duration (Days) <span class="text-danger">*</span></label>
                                    <input type="number" name="duration_days" id="duration_days"
                                        class="form-control @error('duration_days') is-invalid @enderror"
                                        value="{{ old('duration_days', $announcement->duration_days) }}" min="1"
                                        max="365" placeholder="7">
                                    @error('duration_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        How many days the announcement will be displayed (1-365 days).
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror">
                                <option value="active"
                                    {{ old('status', $announcement->status) === 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive"
                                    {{ old('status', $announcement->status) === 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Announcement status. Only announcements with "Active" status will be displayed.
                            </small>
                        </div>

                        <!-- Info Section -->
                        <div class="alert alert-light">
                            <h6><i class="fas fa-info-circle"></i> Announcement Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small><strong>Created by:</strong> {{ $announcement->creator->name }}</small><br>
                                    <small><strong>Created at:</strong>
                                        {{ $announcement->created_at->format('d/m/Y H:i') }}</small><br>
                                    <small><strong>Last updated:</strong>
                                        {{ $announcement->updated_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Current status:</strong>
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
                                    </small><br>
                                    <small><strong>End date:</strong>
                                        {{ $announcement->end_date->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        </div>



                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Announcement
                        </button>
                        <a href="{{ route('announcements.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <!-- Summernote -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.css') }}">
@endsection

@section('scripts')
    <!-- Summernote -->
    <script src="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('#content').summernote({
                height: 200,
                placeholder: 'Enter announcement content...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endsection
