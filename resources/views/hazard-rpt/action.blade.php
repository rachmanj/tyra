<form action="{{ route('hazard-rpt.destroy', $model->id) }}" method="POST" id="delete-report">
    @csrf
    @method('DELETE')
    
</form>
<a href="{{ route('hazard-rpt.show', $model->id) }}" class="btn btn-info btn-xs"> show</a>
{{-- <a href="{{ route('hazard-rpt.close_report', $model->id) }}" class="btn btn-xs btn-warning" onclick="return confirm('Are you sure to close this record?')"> close</a> --}}

<button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to delete this record?')" form="delete-report" {{ $model->attachments->count() > 0 || $model->responses->count() > 0 ? 'disabled' : '' }} >delete</button>

