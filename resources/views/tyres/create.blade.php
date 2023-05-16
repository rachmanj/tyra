@extends('templates.main')

@section('title_page')
Tyres
@endsection

@section('breadcrumb_title')
tyres
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">New Tyre</h3>
                <a href="{{ route('tyres.index') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-arrow-left"></i> Back</a>
            </div>

            <form action="{{ route('tyres.store') }}" method="POST">
                @csrf
                <div class="card-body">

                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <label for="reg_no">Serial Number</label>
                                <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}" class="form-control @error('serial_number') is-invalid @enderror">
                                @error('serial_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="is_new">Tyre Status</label><br>
                                <input type="radio" value="1" name="is_new" checked>
                                <label for="is_new">New</label>
                                <input type="radio" value="0" name="is_new">
                                <label for="is_new">Used</label>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="size_id">Size</label>
                                <select name="size_id" class="form-control select2bs4 @error('size_id') is-invalid @enderror">
                                    <option value="">-- select Tyre Size --</option>
                                    @foreach ($sizes as $size)
                                    <option value="{{ $size->id }}" {{ $size->id == old('size_id') ? "selected" : "" }}>{{ $size->description }}</option>
                                    @endforeach
                                </select>
                                @error('size_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="brand_id">Brand</label>
                                <select name="brand_id" class="form-control select2bs4 @error('brand_id') is-invalid @enderror">
                                    <option value="">-- select Tyre Brand --</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $brand->id == old('brand_id') ? "selected" : "" }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="pattern_id">Pattern</label>
                                <select name="pattern_id" class="form-control select2bs4 @error('pattern_id') is-invalid @enderror">
                                    <option value="">-- select Tyre Pattern --</option>
                                    @foreach ($patterns as $pattern)
                                    <option value="{{ $pattern->id }}" {{ $pattern->id == old('pattern_id') ? "selected" : "" }}>{{ $pattern->name }}</option>
                                    @endforeach
                                </select>
                                @error('pattern_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="po_no">PO No</label>
                                <input type="text" name="po_no" id="po_no" value="{{ old('po_no') }}" class="form-control @error('po_no') is-invalid @enderror">
                                @error('po_no')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="do_no">DO No</label>
                                <input type="text" name="do_no" id="do_no" value="{{ old('do_no') }}" class="form-control @error('do_no') is-invalid @enderror">
                                @error('do_no')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="otd">OTD</label>
                                <input type="text" name="otd" id="otd" value="{{ old('otd') }}" class="form-control @error('otd') is-invalid @enderror">
                                @error('otd')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="pressure">Pressure</label>
                                <input type="text" name="pressure" id="pressure" value="{{ old('pressure') }}" class="form-control @error('pressure') is-invalid @enderror">
                                @error('pressure')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="hours_target">Hours Target</label>
                                <input type="text" name="hours_target" id="hours_target" value="{{ old('hours_target') }}" class="form-control @error('hours_target') is-invalid @enderror">
                                @error('hours_target')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="supplier_id">Vendor</label>
                                <select name="supplier_id" class="form-control select2bs4 @error('supplier_id') is-invalid @enderror">
                                    <option value="">-- select Vendor --</option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $supplier->id == old('supplier_id') ? "selected" : "" }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" name="price" id="price" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror">
                                @error('price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="receive_date">Receive Date</label>
                                <input type="date" name="receive_date" id="receive_date" value="{{ old('receive_date') }}" class="form-control @error('receive_date') is-invalid @enderror">
                                @error('receive_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="current_project">Project</label>
                                <select id="current_project" name="current_project" class="form-control select2bs4 @error('current_project') is-invalid @enderror">
                                    <option value="">-- select current project --</option>
                                    @foreach ($projects as $project)
                                    <option value="{{ $project['project_code'] }}" {{ $project['project_code'] === old('current_project') ? "selected" : "" }}>{{ $project['project_code'] }}</option>
                                    @endforeach
                                </select>
                                @error('current_project')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="warranty_exp_date">Warranty Expire Date</label>
                                <input type="date" name="warranty_exp_date" id="warranty_exp_date" value="{{ old('warranty_exp_date') }}" class="form-control @error('warranty_exp_date') is-invalid @enderror">
                                @error('warranty_exp_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="warranty_exp_hm">Warranty Expire HM</label>
                                <input type="text" name="warranty_exp_hm" id="warranty_exp_hm" value="{{ old('warranty_exp_hm') }}" class="form-control @error('warranty_exp_hm') is-invalid @enderror">
                                @error('warranty_exp_hm')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="cph">CPH</label>
                                <input type="text" id="cph" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                </div> <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-primary btn-block">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>
</div>
@endsection

@section('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
{{-- axios --}}
<script src="{{ asset('adminlte/axios/axios.min.js') }}"></script>
<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    })
</script>
<script>
    // on change price or hours target calculate cph
    $('#price, #hours_target').on('change', function() {
        var price = $('#price').val();
        var hours_target = $('#hours_target').val();
        var cph = price / hours_target;
        $('#cph').val(cph.toFixed(2));
    });
</script>
@endsection