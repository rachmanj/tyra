@extends('templates.main')

@section('title_page')
    Dashboard
@endsection

@section('breadcrumb_title')
    dashboard
@endsection

@section('content')
    {{-- BAR CHART : HAZARD BY PROJECTS --}}
    <div class="row">
      <div class="col-12">
          @include('dashboard.chart1')
      </div>
    </div>
    {{-- CHART --}}

    
    {{-- PIE CHART : HAZARD BY CATEGORIES --}}
    <div class="row">
        <div class="col-12">
            @include('dashboard.by-category-chart')
        </div>
      </div>
      {{-- CHART --}}
@endsection

@section('scripts')
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
    // tooltip
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
</script>

 {{-- CHART SCRIPT --}}
 @include('dashboard.chart-script')

@endsection