@extends('templates.main')

@section('title_page')
    New Hazard Report
@endsection

@section('breadcrumb_title')
    hazard-report
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-undo"></i> Back</a>
                </div>

                <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="reg_no">Reg No<small>(Reg No will change according to recent data)</small></label>
                                    <input name="reg_no" id="reg_no" value="{{ $nomor }}" class="form-control @error('reg_no') is-invalid @enderror" readonly>
                                    @error('reg_no')
                                      <div class="invalid-feedback">
                                        {{ $message }}
                                      </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">Vendor Name</label>
                                    <input name="name" id="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
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
                                    <label for="sap_code">SAP Code</label>
                                    <input name="sap_code" id="sap_code" value="{{ old('sap_code') }}" class="form-control @error('sap_code') is-invalid @enderror">
                                    @error('sap_code')
                                      <div class="invalid-feedback">
                                        {{ $message }}
                                      </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                              <div class="form-group">
                                  <label for="badan_hukum">Badan Hukum</label>
                                  <select name="badan_hukum" class="form-control">
                                    @foreach ($badan_hukum as $item)
                                      <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                  </select>
                              </div>
                          </div>
                          <div class="col-4">
                            <div class="form-group">
                                <label for="npwp">NPWP</label>
                                <input name="npwp" id="npwp" value="{{ old('npwp') }}" class="form-control @error('npwp') is-invalid @enderror">
                                @error('npwp')
                                  <div class="invalid-feedback">
                                    {{ $message }}
                                  </div>
                                @enderror
                            </div>
                        </div>
                        </div>

                        <div class="row">
                          {{-- make 3 colums --}}

                          <div class="col-12">
                            <div class="form-group">
                              <label>Specifications</label>
                              <select name="specifications[]" class="select2 form-control @error('experience') is-invalid @enderror" multiple="multiple" data-placeholder="Select specifications" style="width: 100%;">
                                @foreach ($specifications as $item)
                                  <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                              </select>
                              @error('specifications')
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
                                    <label for="experience">Established <small>(Thn pendirian usaha)</small></label>
                                    <input type="text" name="experience" id="experience" value="{{ old('experience') }}" class="form-control @error('experience') is-invalid @enderror">
                                    @error('experience')
                                      <div class="invalid-feedback">
                                        {{ $message }}
                                      </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="jumlah_karyawan">Number of Employees</label>
                                    <input name="jumlah_karyawan" id="jumlah_karyawan" value="{{ old('jumlah_karyawan') }}" class="form-control @error('jumlah_karyawan') is-invalid @enderror">
                                    @error('jumlah_karyawan')
                                      <div class="invalid-feedback">
                                        {{ $message }}
                                      </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="banned">Banned</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                          <div class="col-12">
                            {{-- text area of description --}}
                            <div class="form-group">
                              <label for="remarks">Remarks</label>
                              <textarea name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3">{{ old('remarks') }}</textarea>
                              @error('remarks')
                                <div class="invalid-feedback">
                                  {{ $message }}
                                </div>
                              @enderror
                            </div>
                          </div>
                        </div>
                      
                    </div> <!-- /.card-body -->

               

                <div class="card-header">
                    <h3 class="card-title">Legalitas</h3>
                  <button type="button" id="add_row" class="btn btn-sm btn-primary float-right">Add more attachment</button>
                </div>
                <div class="card-body">
                   <table class="table">
                    <thead>
                      <tr>
                        <th>Attachment</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="attachment_table">
                      <tr>
                        <td>
                          <input type="file" name="file_upload[]" id="file" class="form-control">
                        </td>
                        <td>
                          <button class="btn btn-xs btn-danger remove_row">delete</button>
                        </td>
                      </tr>
                    </tbody>
                    </table>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-success btn-block">Submit</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
  
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })
</script>
@endsection