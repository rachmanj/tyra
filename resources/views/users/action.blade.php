@if ($model->is_active == 1)
  <form action="{{ route('users.deactivate', $model->id) }}" method="POST">
    @csrf @method('PUT')
      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-xs btn-warning">deactivate
      </button>
  </form>
@endif

@if ($model->is_active == 0)
  <form action="{{ route('users.activate', $model->id) }}" method="POST">
    @csrf @method('PUT')
      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-xs btn-warning">
      activate
      </button>
  </form>
@endif

{{-- @can('edit_user') --}}
<a href="{{ route('users.edit', $model->id) }}" class="btn btn-xs btn-info">edit</a>
{{-- @endcan --}}

<form action="{{ route('users.destroy', $model->id) }}" method="POST">
  @csrf @method('DELETE')
  @can('delete_user')
    <button class="btn btn-xs btn-danger" type="submit" onclick="return confirm('Are You sure You want to delete this user?')">delete</button>
  @endcan
</form>