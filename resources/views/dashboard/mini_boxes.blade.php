<div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-circle"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Active Tyres</span>
            <span class="info-box-number">{{ $active_tyre_count }}</span>
        </div>
    </div>
</div>

<div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-coins"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Avg CPH Active Tyres</span>
            <span class="info-box-number">Rp. {{ $avg_active }}</span>
        </div>
    </div>
</div>

<div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-history"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Avg CPH In-Active Tyres</span>
            <span class="info-box-number">Rp. {{ $avg_inactive }}</span>
        </div>
    </div>
</div>

<div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-archive"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Inactive Tyres</span>
            <span class="info-box-number">{{ $inactive_tyre_count }}</span>
        </div>
    </div>
</div>
