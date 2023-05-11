@extends('templates.main')

@section('title_page')
    Dashboard
@endsection

@section('breadcrumb_title')
    dashboard
@endsection

@section('content')
    
    <div class="row">
        @include('dashboard.mini_boxes')
    </div>
    <div class="row">
        <div class="col-12">
            @include('dashboard.active_tyre_by_project')
        </div>
    </div>
    
@endsection

@section('scripts')
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
{{-- CHART SCRIPT --}}
@include('dashboard.chart_active_tyres')
@endsection