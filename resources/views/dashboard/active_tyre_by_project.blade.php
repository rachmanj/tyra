<div class="card card-info">
    <div class="card-header border-0">
        <div class="d-flex justify-content-between">
            <h3 class="card-title">Active Tyres</h3>
            {{-- <a href="javascript:void(0);">View Report</a> --}}
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex">
            <p class="d-flex flex-column">
            <span class="text-bold text-lg">{{ $active_tyres->count() }}</span>
            <span>Total Active Tyres</span>
            </p>
            <p class="ml-auto d-flex flex-column text-right">
            <span class="text-success">
                {{-- <i class="fas fa-arrow-up"></i> 33.1% --}}
            </span>
            {{-- <span class="text-muted">Since last month</span> --}}
            </p>
        </div>
    <!-- /.d-flex -->

        <div class="position-relative mb-4">
            <canvas id="active-tyres-chart" height="200"></canvas>
        </div>

        <div class="d-flex flex-row justify-content-end">
            <span class="mr-2">
                <i class="fas fa-square text-primary"></i> This year
            </span>

            <span>
                {{-- <i class="fas fa-square text-gray"></i> Last year --}}
            </span>
        </div>
    </div>
</div>