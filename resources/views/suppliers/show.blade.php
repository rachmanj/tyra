@extends('templates.main')

@section('title_page')
    Vendors
@endsection

@section('breadcrumb_title')
    suppliers
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"> Vendor Detail</h3>
                <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-undo"></i> Back</a>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Reg No</dt>
                    <dd class="col-sm-8">: {{ $supplier->reg_no }}</dd>
                    <dt class="col-sm-4">Name</dt>
                    <dd class="col-sm-8">: <b>{{ $supplier->name . ', ' . $supplier->badan_hukum }}</b></dd>
                    <dt class="col-sm-4">NPWP</dt>
                    <dd class="col-sm-8">: {{ $supplier->npwp }}</dd>
                    <dt class="col-sm-4">Established | Experience</dt>
                    <dd class="col-sm-8">: {{ $supplier->experience ? $supplier->experience . ' | ' . date('Y') - $supplier->experience . ' years' : '-'  }}</dd>
                    <dt class="col-sm-4">Specifications</dt>
                    <dd class="col-sm-8">: 
                    @foreach ($supplier->specifications as $spec)
                        <span class="badge badge-success">{{ $spec->name }}</span>
                    @endforeach
                    </dd>
                    <dt class="col-sm-4">Number of Employees</dt>
                    <dd class="col-sm-8">: {{ $supplier->jumlah_karyawan }} employees</dd>
                    <dt class="col-sm-4">Account Officer</dt>
                    <dd class="col-sm-8">: {{ $supplier->account_officer ? $supplier->accountOfficer->name : '-' }}</dd>
                    <dt class="col-sm-4">Created by</dt>
                    <dd class="col-sm-8">: {{ $supplier->createdBy->name }}</dd>
                    <dt class="col-sm-4">Remarks</dt>
                    <dd class="col-sm-8">: {{ $supplier->remarks }}</dd>
                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8">: 
                        @if ($supplier->status === "banned")
                            <span class="badge badge-danger">Banned</span>
                        @elseif ($supplier->status === "active")
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-warning">{{ $supplier->status }}</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>

@endsection