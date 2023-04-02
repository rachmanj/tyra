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
                <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Reg No</dt>
                    <dd class="col-sm-8">: {{ $supplier->reg_no }}</dd>
                    <dt class="col-sm-4">Name</dt>
                    <dd class="col-sm-8">: <b>{{ $supplier->name . ', ' . $supplier->badan_hukum }}</b></dd>
                    <dt class="col-sm-4">NPWP</dt>
                    <dd class="col-sm-8">: {{ $supplier->npwp ? $supplier->npwp : '-' }}</dd>
                    <dt class="col-sm-4">Address</dt>
                    <dd class="col-sm-8">: {{ $address }}</dd>
                    <dt class="col-sm-4">Phone</dt>
                    <dd class="col-sm-8">: {{ $supplier->phone ? $supplier->phone : '-'  }}</dd>
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8">: {{ $supplier->email ? $supplier->email : '-' }}</dd>
                    <dt class="col-sm-4">Website</dt>
                    <dd class="col-sm-8">: {{ $supplier->website ? $supplier->website : '-' }}</dd>
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
                <h3 class="card-title">Contact Persons</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Phone</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supplier->contacts as $contact)
                            <tr>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->position }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td>{{ $contact->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection