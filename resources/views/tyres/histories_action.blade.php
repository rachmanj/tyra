{{-- destroy button with alert confirmation --}}
<form action="{{ route('tyres.transaction.destroy', $model->id) }}" method="POST" class="d-inline">
    @csrf @method('DELETE')
    <input type="hidden" name="form_type" value="show_tyre">
    <a href="{{ route('transactions.edit', $model->id) }}" class="btn btn-xs btn-warning">edit</a>
    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want delete this record?')">delete</button>
</form>