<button type="button" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#modal-edit-{{ $model->id }}">edit</button>

{{-- destroy button with alert confirmation --}}
<form action="{{ route('suppliers.destroy', $model->id) }}" method="POST" class="d-inline">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want delete this record?')">delete</button>
</form>

{{-- Modal edit --}}
<div class="modal fade" id="modal-edit-{{ $model->id }}">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Supplier</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('suppliers.update', $model->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Vendor Name</label>
                        <input name="name" value="{{ old('name', $model->name) }}" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ $model->name }}" autofocus>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer float-left">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save changes</button>
            </form>
        </div>
    </div>
</div>