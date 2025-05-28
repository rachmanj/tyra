@php
    $activeAnnouncements = \App\Models\Announcement::activeAndCurrent()->orderBy('created_at', 'desc')->get();
@endphp

@if ($activeAnnouncements->count() > 0)
    <div class="row mb-3">
        <div class="col-12">
            @foreach ($activeAnnouncements as $announcement)
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <h5><strong>📢 Announcement</strong></h5>
                    <div style="line-height: 1.6;">{!! $announcement->content !!}</div>
                    <hr>
                    <small class="text-white">
                        <i class="fas fa-calendar-alt"></i>
                        <strong>Period:</strong> {{ $announcement->start_date->format('d/m/Y') }} -
                        {{ $announcement->end_date->format('d/m/Y') }}
                        ({{ $announcement->duration_days }} days)
                        @if (auth()->user()->hasRole('superadmin'))
                            | <i class="fas fa-user"></i> <strong>Created by:</strong>
                            {{ $announcement->creator->name }}
                            | <a href="{{ route('announcements.show', $announcement) }}" class="text-white">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        @endif
                    </small>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endforeach
        </div>
    </div>
@endif
