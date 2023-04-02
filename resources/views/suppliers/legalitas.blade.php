@extends('templates.main')

@section('title_page')
    Vendor's Documents
@endsection

@section('breadcrumb_title')
    vendors
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
                </dl>
            </div>

            <div class="card-header">
                <h3 class="card-title">Legalitas</h3>
                {{-- add document button that show up modal --}}
                <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#modal-add-document">
                    <i class="fas fa-plus"></i> Add Document
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Doc Type</th>
                            <th>Doc No</th>
                            <th>Remarks</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                       {{-- if no documents --}}
                        @if (count($supplier->documents) == 0)
                            <tr>
                                <td colspan="4" class="text-center">No documents found</td>
                            </tr>
                        @endif
                       
                        @foreach ($supplier->documents as $document)
                            <tr>
                                <td>{{ $document->type }}</td>
                                <td>{{ $document->number }}</td>
                                <td>{{ $document->remarks }}</td>
                                <td>
                                    {{-- show document --}}
                                    @if ($document->filename) <a href="{{ asset('document_upload/') . '/'. $document->filename }}" class='btn btn-xs btn-success' target=_blank>Show</a> @endif
                                    {{-- edit document --}}
                                    @can('edit_document')
                                    <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#modal-edit-{{ $document->id }}">edit</button>
                                    @endcan
                                    {{-- delete document --}}
                                    <form action="{{ route('suppliers.legalitas.destroy', $document->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        @can('delete_document')
                                        <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want delete this record?')">delete</button>
                                        @endcan
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal create --}}
<div class="modal fade" id="modal-add-document">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> New Document</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('suppliers.legalitas.store', $supplier->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
        <div class="modal-body">
  
          <div class="form-group">
            <label for="document_type">Document Type</label>
            <select name="document_type" id="document_type" class="form-control select2bs4 @error('document_type') is-invalid @enderror">
                <option value="">-- select document type --</option>
                @foreach ($document_types as $type)
                    <option value="{{ $type->name }}">{{ $type->name }}</option>
                @endforeach
              </select>
                @error('document_type')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
          </div>
  
          <div class="form-group">
            <label for="document_no">Doc Number</label>
            <input type="text" name="document_no" id="document_no" class="form-control @error('document_no') is-invalid @enderror">
            @error('document_no')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
            @enderror
          </div>
  
          <div class="form-group">
            <label for="remarks">Remarks</label>
            <input type="text" name="remarks" id="remarks" class="form-control">
          </div>
  
          <div class="form-group">
            <label for="file_upload">Upload File</label>
            <input type="file" name="file_upload" id="file_upload" class="form-control @error('file_upload') is-invalid @enderror">
            @error('file_upload')
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

  @foreach ($supplier->documents as $document)
    {{-- Modal Edit --}}
    <div class="modal fade" id="modal-edit-{{ $document->id }}">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"> Edit Document</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{ route('suppliers.legalitas.update', $document->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
          <div class="modal-body">
    
            <div class="form-group">
              <label for="document_type">Document Type</label>
              <select name="document_type" id="document_type" class="form-control select2bs4">
                  @foreach ($document_types as $type)
                      <option value="{{ $type->name }}" {{ $document->type == $type->name ? "selected" : "" }}>{{ $type->name }}</option>
                  @endforeach
              </select>
            </div>
    
            <div class="form-group">
              <label for="document_no">Doc Number</label>
              <input type="text" name="document_no" value="{{ $document->number }}" id="document_no" class="form-control @error('document_no') is-invalid @enderror">
              @error('document_no')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
              @enderror
            </div>
    
            <div class="form-group">
              <label for="remarks">Remarks</label>
              <input type="text" name="remarks" value="{{ $document->remarks }}" id="remarks" class="form-control">
            </div>
    
            <div class="form-group">
              <label for="file_upload">Upload File</label>
              <input type="file" name="file_upload" id="file_upload" class="form-control">
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