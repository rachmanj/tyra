{{-- destroy button with alert confirmation --}}
<form action="{{ route('suppliers.destroy', $model->id) }}" method="POST" class="d-inline">
    @csrf @method('DELETE')
    {{-- show --}}
    <a href="{{ route('suppliers.show', $model->id) }}" class="btn btn-xs btn-info">show</a>
    {{-- edit --}}
    <a href="{{ route('suppliers.edit', $model->id) }}" class="btn btn-xs btn-warning"> edit</a>
    {{-- delete --}}
    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want delete this record?')">delete</button>
</form>
