
{{-- destroy button with alert confirmation --}}
<form action="{{ route('tyre-sizes.destroy', $model->id) }}" method="POST" class="d-inline">
    @csrf @method('DELETE')
    <a href="{{ route('tyres.edit', $model->id) }}" class="btn btn-xs btn-warning">edit</a>
    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want delete this record?')">delete</button>
</form>
