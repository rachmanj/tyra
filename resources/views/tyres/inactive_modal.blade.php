{{-- MODAL INACTIVE TYRE --}}
<div class="modal fade" id="tyre_inactive" tabindex="-1" role="dialog" aria-labelledby="tyreInactiveLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tyreInactiveLabel">Deactivate Tyre</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('tyres.inactive', $tyre->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inactive_reason">Reason for Deactivation <span class="text-danger">*</span></label>
                        <select class="form-control" id="inactive_reason" name="inactive_reason" required>
                            <option value="">Select Reason</option>
                            <option value="Scrap">Scrap</option>
                            <option value="Breakdown">Breakdown</option>
                            <option value="Repair">Repair</option>
                            <option value="Consignment">Consignment</option>
                            <option value="Rotable">Rotable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="inactive_notes">Notes (Optional)</label>
                        <textarea class="form-control" id="inactive_notes" name="inactive_notes" rows="3"
                            placeholder="Additional notes about the deactivation..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Warning:</strong> This will deactivate the tyre. You can reactivate it later if needed.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Deactivate Tyre</button>
                </div>
            </form>
        </div>
    </div>
</div>
