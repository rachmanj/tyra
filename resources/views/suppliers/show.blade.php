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
                        <button class="btn btn-xs btn-outline-success" style="pointer-events: none;">{{ $spec->name }}</button>
                    @endforeach
                    </dd>
                    <dt class="col-sm-4">Principal Product</dt>
                    <dd class="col-sm-8">: 
                    @foreach ($supplier->brands as $brand)
                    <button class="btn btn-xs btn-outline-success" style="pointer-events: none;">{{ $brand->name }}</button>
                    @endforeach
                    </dd>
                    <dt class="col-sm-4">Number of Employees</dt>
                    <dd class="col-sm-8">: {{ $supplier->jumlah_karyawan }} employees</dd>
                    <dt class="col-sm-4">Account Officer</dt>
                    <dd class="col-sm-8">: {{ $supplier->account_officer ? $supplier->accountOfficer->name : '-' }}</dd>
                    
                    <dt class="col-sm-4">Remarks</dt>
                    <dd class="col-sm-8">: {{ $supplier->remarks }}</dd>
                    <dt class="col-sm-4">Legalitas</dt>
                    <dd class="col-sm-8">: 
                    @foreach ($supplier->documents as $doc)
                        <a href="{{ asset('document_upload/') . '/'. $doc->document_filename }}"  class="btn btn-sm btn-outline-success"  target=_blank>{{ $doc->document_type }} {{ $doc->document_number ? "No. " . $doc->document_number : "" }} </a>
                    @endforeach
                    </dd>
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
                    <dt class="col-sm-4">Created by</dt>
                    <dd class="col-sm-8">: {{ $supplier->createdBy->name }}</dd>
                </dl>
            </div>

            <div class="card-header">
                <h3 class="card-title">Address</h3>
            </div>
            <div class="card-body">
                <div class="col-md-4">
                <!-- Widget: user widget style 2 -->
                
                <div class="card-widget widget-user-2">
                    <div class="ribbon-wrapper">
                        <div class="ribbon bg-primary">
                          Default
                        </div>
                      </div>
                  <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-warning">
                    <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">Nadia Carmichael</h3>
                        <h5 class="widget-user-desc">Lead Developer</h5>
                    </div>
                    <div class="card-footer p-0">
                        <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                            Projects <span class="float-right badge bg-primary">31</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                            Tasks <span class="float-right badge bg-info">5</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                            Completed Projects <span class="float-right badge bg-success">12</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                            Followers <span class="float-right badge bg-danger">842</span>
                            </a>
                        </li>
                        </ul>
                    </div>
                </div>
                <!-- /.widget-user -->
              </div>
            </div>
            <div class="card-header">
                <h3 class="card-title">Contact Person</h3>
            </div>
        </div>
    </div>
</div>

@endsection