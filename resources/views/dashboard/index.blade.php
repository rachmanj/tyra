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
    
@endsection

@section('scripts')
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
@endsection