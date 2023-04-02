{{-- destroy button with alert confirmation --}}
<form action="{{ route('suppliers.destroy', $model->id) }}" method="POST" class="d-inline">
    @csrf @method('DELETE')
    {{-- show --}}
    <a href="{{ route('suppliers.show', $model->id) }}" class="btn btn-xs btn-info">show</a>
    {{-- add / edit documents --}}
    @can('akses_legalitas')
    <a href="{{ route('suppliers.legalitas', $model->id) }}" class="btn btn-xs btn-primary">add/edit docs</a>
    @endcan
    {{-- edit --}}
    @can('edit_vendor')
    <a href="{{ route('suppliers.edit', $model->id) }}" class="btn btn-xs btn-warning"> edit</a>
    @endcan
    {{-- delete --}}
    @can('delete_vendor')
    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want delete this record? This action will delete all records connected to vendor including documents and contacts')">delete</button>
    @endcan
</form>
