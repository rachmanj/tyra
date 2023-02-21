<form action="{{ route('hazard-responses.destroy', $model->id) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="hazard_report_id" value="{{ $model->hazard_report_id }}">
    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to delete this record?')">delete</button>
</form>