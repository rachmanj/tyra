@extends('templates.main')

@section('title_page')
  Reports  
@endsection

@section('breadcrumb_title')
    reports
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Reports</h3>
            </div>

            <div class="card-body">
                <ol>
                    <li>Tyres</li>
                    <ol>
                        <li><a href="{{ route('reports.tyre-rekaps') }}">Tyre Rekaps</a></li>
                    </ol>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection