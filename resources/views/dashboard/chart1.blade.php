<div class="card card-info">
    <div class="card-header border-0">
        <div class="d-flex justify-content-between">
            <h3 class="card-title">Hazard Report by Projects</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex">
            <p class="d-flex flex-column">
            {{-- <span class="text-bold text-lg">IDR {{ number_format($this_year_outgoings['amounts']->sum('amount'), 0) }}</span> --}}
            <span>This Year</span>
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
            <canvas id="hazard-chart" height="200"></canvas>
        </div>

        <div class="d-flex flex-row justify-content-end">
            <span class="mr-2">
                <i class="fas fa-square text-primary"></i> Pending
            </span>

            <span>
                <i class="fas fa-square text-gray"></i> Closed
            </span>
        </div>
    </div>
</div>