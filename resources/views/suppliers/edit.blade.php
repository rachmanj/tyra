@extends('templates.main')

@section('title_page')
    Edit Vendor
@endsection

@section('breadcrumb_title')
    vendors
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header">
                <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-arrow-left"></i> Back</a>
            </div>

            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="reg_no">Reg No</label>
                                <input name="reg_no" id="reg_no" value="{{ $supplier->reg_no }}" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Vendor Name</label>
                                <input name="name" id="name" value="{{ old('name', $supplier->name) }}" class="form-control @error('name') is-invalid @enderror">
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
                                <input name="sap_code" id="sap_code" value="{{ old('sap_code', $supplier->sap_code) }}" class="form-control @error('sap_code') is-invalid @enderror">
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
                                  <option value="{{ $item }}" {{ $supplier->badan_hukum == $item ? "selected" : "" }}>{{ $item }}</option>
                                @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                            <label for="npwp">NPWP</label>
                            <input name="npwp" id="npwp" value="{{ old('npwp', $supplier->npwp) }}" class="form-control @error('npwp') is-invalid @enderror">
                            @error('npwp')
                              <div class="invalid-feedback">
                                {{ $message }}
                              </div>
                            @enderror
                        </div>
                    </div>
                    </div>

                    {{-- divider --}}
                    <div class="row">
                      <div class="col-12">
                        <hr>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="email">Emails</small></label>
                          <input class="form-control" type="text" name="email" value="{{ old('email', $supplier->email) }}" class="form-control">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="phone">Phones</label>
                          <input class="form-control" type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" class="form-control">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="website">Website</label>
                          <input class="form-control" type="text" name="website" value="{{ old('website', $supplier->website) }}" class="form-control">
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="address1">Address1</label>
                          <input class="form-control" type="text" name="address1" value="{{ old('address1', $supplier->address1) }}" class="form-control">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="address2">Address2</label>
                          <input class="form-control" type="text" name="address2" value="{{ old('address2', $supplier->address2) }}" class="form-control">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="city">City</label>
                          <input class="form-control" type="text" name="city" value="{{ old('city', $supplier->city) }}" class="form-control">
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="province">Province</label>
                          <input class="form-control" type="text" name="province" value="{{ old('province', $supplier->province) }}" class="form-control">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="country">Country</label>
                          <input class="form-control" type="text" name="country" value="{{ old('country', $supplier->country) }}" class="form-control">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="postal_code">Postal Code</label>
                          <input class="form-control" type="text" name="postal_code" value="{{ old('postal_code', $supplier->postal_code) }}" class="form-control">
                        </div>
                      </div>
                    </div>

                    {{-- divider --}}
                    <div class="row">
                      <div class="col-12">
                        <hr>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <label>Specifications <small>(choose one or more)</small></label>
                          <div class="select2-purple">
                            <select name="specifications[]" class="select2 form-control" data-dropdown-css-class="select2-purple" multiple="multiple" data-placeholder="Select specifications" style="width: 100%;">
                              @foreach ($specifications as $item)
                                {{-- show selected --}}
                                <option value="{{ $item->id }}" {{ $supplier->specifications->contains($item->id) ? "selected" : "" }}>{{ $item->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <label>Principal Products <small>(choose one or more)</small></label>
                          <div class="select2-purple">
                            <select name="brands[]" class="select2 form-control @error('brands') is-invalid @enderror" multiple="multiple" data-placeholder="Select brands" style="width: 100%;">
                              @foreach ($brands as $item)
                                {{-- show selected --}}
                                <option value="{{ $item->id }}" {{ $supplier->brands->contains($item->id) ? "selected" : "" }}>{{ $item->name }}</option>
                              @endforeach
                            </select>
                          </div>
                          @error('brands')
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
                                <input type="text" name="experience" id="experience" value="{{ old('experience', $supplier->experience) }}" class="form-control @error('experience') is-invalid @enderror">
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
                                <input name="jumlah_karyawan" id="jumlah_karyawan" value="{{ old('jumlah_karyawan', $supplier->jumlah_karyawan) }}" class="form-control @error('jumlah_karyawan') is-invalid @enderror">
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
                                    <option value="active" {{ $supplier->status == 'active' ? "selected" : "" }}>Active</option>
                                    <option value="inactive" {{ $supplier->status == 'inactive' ? "selected" : "" }}>Inactive</option>
                                    <option value="banned" {{ $supplier->status == 'banned' ? "selected" : "" }}>Banned</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-12">
                        {{-- text area of description --}}
                        <div class="form-group">
                          <label for="remarks">Remarks</label>
                          <textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks', $supplier->remarks) }}</textarea>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                        <button type="submit" class="btn btn-success btn-block">Save Changes</button>
                    </div>
                  
                </div> <!-- /.card-body -->
            </form>

                {{-- CONTACTS --}}
                <div class="card-header">
                    <h3 class="card-title">Contacts</h3>
                    {{-- new contact button that call modal create --}}
                    <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#contact-create"><i class="fas fa-plus"></i> Contact</button>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($supplier->contacts as $contact)
                                <tr>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->position }}</td>
                                    <td>{{ $contact->phone }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>
                                        @can('edit_contact'')
                                        <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#contact-edit-{{ $contact->id }}">edit</button>
                                        @endcan
                                      
                                        <form action="{{ route('suppliers.contact.destroy', $contact->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            @can('delete_contact')
                                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want delete this contact?')">Delete</button>
                                            @endcan
                                        </form>
                                </tr>
                            @endforeach
                        </tbody>
            
                    </table>
                </div>
        </div>
    </div>
</div>

{{-- Modal Create --}}
<div class="modal fade" id="contact-create">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title"> Edit Contact</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <form action="{{ route('suppliers.contact.store') }}" method="POST">
        @csrf
        <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
        <div class="modal-body">

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control @error('name') is-invalid @enderror">
            @error('name')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="position">Position</label>
            <input type="text" name="position" value="{{ old('position') }}" id="position" class="form-control">
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" id="phone" class="form-control @error('phone') is-invalid @enderror">
            @error('phone')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" value="{{ old('email') }}" id="email" class="form-control @error('email') is-invalid @enderror">
            @error('email')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        </div> <!-- /.modal-body -->
        <div class="modal-footer float-left">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"> Close</button>
            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Save</button>
        </div>
    </form>
    </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>


    @foreach ($supplier->contacts as $contact)
    {{-- Modal Edit --}}
    <div class="modal fade" id="contact-edit-{{ $contact->id }}">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title"> Edit Contact</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form action="{{ route('suppliers.contact.update', $contact->id) }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
            <div class="modal-body">

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" value="{{ $contact->name }}" id="name" class="form-control @error('name') is-invalid @enderror">
                @error('name')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="position">Position</label>
                <input type="text" name="position" value="{{ $contact->position }}" id="position" class="form-control">
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" value="{{ $contact->phone }}" id="phone" class="form-control @error('phone') is-invalid @enderror">
                @error('phone')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" value="{{ $contact->email }}" id="email" class="form-control @error('email') is-invalid @enderror">
                @error('email')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
                @enderror
            </div>

            </div> <!-- /.modal-body -->
            <div class="modal-footer float-left">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"> Close</button>
                <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
        </div> <!-- /.modal-content -->
        </div> <!-- /.modal-dialog -->
    </div>
    @endforeach
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