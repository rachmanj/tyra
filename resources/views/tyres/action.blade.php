{{-- destroy button with alert confirmation --}}
<form action="{{ route('tyres.destroy', $model->id) }}" method="POST" class="d-inline">
    @csrf @method('DELETE')
    <a href="{{ route('tyres.show', $model->id) }}" class="btn btn-xs btn-success">show</a>
    <a href="{{ route('tyres.edit', $model->id) }}" class="btn btn-xs btn-warning">edit</a>
    {{-- if tyre has transactions then button is disabled --}}
    @if ($model->transactions->count() > 0)
    <button class="btn btn-xs btn-danger" disabled>delete</button>
    @else
    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want delete this record?')">delete</button>
    @endif
</form>
