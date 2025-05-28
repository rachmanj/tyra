@extends('templates.main')

@section('title_page')
    Detail Announcement
@endsection

@section('breadcrumb_title')
    announcements / detail
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Announcement</h3>
                    <div class="card-tools">
                        <div class="btn-group" role="group">
                            <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-secondary mr-2">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm btn-warning mr-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <form action="{{ route('announcements.toggle_status', $announcement) }}" method="POST"
                                style="display: inline;" class="mr-2">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="btn btn-sm {{ $announcement->status === 'active' ? 'btn-secondary' : 'btn-success' }}"
                                    onclick="return confirm('Are you sure you want to change the status of this announcement?')">
                                    <i class="fas {{ $announcement->status === 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                    {{ $announcement->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            <form action="{{ route('announcements.destroy', $announcement) }}" method="POST"
                                style="display: inline;"
                                onsubmit="return confirm('Are you sure you want to delete this announcement? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>


                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Status Badge -->
                    <div class="mb-3">
                        @if ($announcement->status === 'active')
                            @if ($announcement->is_current)
                                <span class="badge badge-success badge-lg">
                                    <i class="fas fa-check-circle"></i> Active & Current
                                </span>
                            @elseif($announcement->is_expired)
                                <span class="badge badge-warning badge-lg">
                                    <i class="fas fa-exclamation-triangle"></i> Active but Expired
                                </span>
                            @else
                                <span class="badge badge-info badge-lg">
                                    <i class="fas fa-clock"></i> Active (Future)
                                </span>
                            @endif
                        @else
                            <span class="badge badge-secondary badge-lg">
                                <i class="fas fa-pause-circle"></i> Inactive
                            </span>
                        @endif
                    </div>

                    <!-- Announcement Content -->
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Announcement Content</h5>
                        <div style="line-height: 1.6;">{!! $announcement->content !!}</div>
                    </div>

                    <!-- Announcement Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-calendar-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Display Period</span>
                                    <span class="info-box-number">
                                        {{ $announcement->start_date->format('d/m/Y') }} -
                                        {{ $announcement->end_date->format('d/m/Y') }}
                                    </span>
                                    <div class="progress">
                                        @php
                                            $today = \Carbon\Carbon::today();
                                            $totalDays = $announcement->duration_days;
                                            $startDate = $announcement->start_date;
                                            $endDate = $announcement->end_date;

                                            if ($today < $startDate) {
                                                $progress = 0;
                                                $progressClass = 'bg-secondary';
                                            } elseif ($today > $endDate) {
                                                $progress = 100;
                                                $progressClass = 'bg-danger';
                                            } else {
                                                $daysPassed = $startDate->diffInDays($today);
                                                $progress = ($daysPassed / $totalDays) * 100;
                                                $progressClass = 'bg-success';
                                            }
                                        @endphp
                                        <div class="progress-bar {{ $progressClass }}" style="width: {{ $progress }}%">
                                        </div>
                                    </div>
                                    <span class="progress-description">
                                        @if ($today < $startDate)
                                            Not started yet ({{ $startDate->diffInDays($today) }} days remaining)
                                        @elseif($today > $endDate)
                                            Already ended ({{ $today->diffInDays($endDate) }} days ago)
                                        @else
                                            {{ $totalDays - $startDate->diffInDays($today) }} days remaining
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Duration</span>
                                    <span class="info-box-number">{{ $announcement->duration_days }} Days</span>
                                    <span class="progress-description">
                                        From {{ $announcement->start_date->format('d M Y') }} to
                                        {{ $announcement->end_date->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Creator & Timestamps -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card card-outline card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Additional Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-user"></i> Created by:</strong><br>
                                            {{ $announcement->creator->name }}<br>
                                            <small class="text-muted">{{ $announcement->creator->email }}</small>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-calendar-plus"></i> Created at:</strong><br>
                                            {{ $announcement->created_at->format('d/m/Y H:i:s') }}<br>
                                            <small
                                                class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-calendar-check"></i> Last updated:</strong><br>
                                            {{ $announcement->updated_at->format('d/m/Y H:i:s') }}<br>
                                            <small
                                                class="text-muted">{{ $announcement->updated_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .badge-lg {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
    </style>
@endsection
