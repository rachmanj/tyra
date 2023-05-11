<div class="col-12 col-sm-6 col-md-4">
    <div class="info-box">
    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Active Tyres</span>
        <span class="info-box-number">
        {{ $active_tyres->count() }}
        <small></small>
        </span>
    </div>
    <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!-- /.col -->
<div class="col-12 col-sm-6 col-md-4">
    <div class="info-box mb-3">
    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-cog"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Avg CPH Active Tyres</span>
        <span class="info-box-number">IDR {{ $avg_active }}</span>
    </div>
    <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!-- /.col -->

<!-- fix for small devices only -->
<div class="clearfix hidden-md-up"></div>

<div class="col-12 col-sm-6 col-md-4">
    <div class="info-box mb-3">
    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-cog"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Avg CPH In-Active Tyres</span>
        <span class="info-box-number">IDR {{ $avg_inactive }}</span>
    </div>
    <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>