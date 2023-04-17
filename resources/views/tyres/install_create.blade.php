{{-- MODAL INSTALL CREATE --}}
<div class="modal fade" id="tyre_install">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title"> Install Tyre</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <form action="{{ route('transactions.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tyre_id" value="{{ $tyre->id }}">
        <input type="hidden" name="form_type" value="show_tyre_install">
        <div class="modal-body">

        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" value="{{ old('date') }}" id="date" class="form-control @error('date') is-invalid @enderror">
                    @error('date')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    <label for="unit_no">Unit No</label>
                    <select name="unit_no" class="form-control select2bs4 @error('unit_no') is-invalid @enderror">
                        <option value="">-- select unit no --</option>
                        @foreach ($equipments as $equipment)
                        <option value="{{ $equipment['unit_code'] }}" {{ $equipment['unit_code'] === old('unit_no') ? "selected" : "" }}>{{ $equipment['unit_code'] . ' - ' . $equipment['plant_group'] . ' - ' . $equipment['model']}}</option>
                        @endforeach
                    </select>
                    @error('unit_no')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label for="position">Position</label>
                    <input type="text" name="position" value="{{ old('position') }}" id="position" class="form-control @error('position') is-invalid @enderror">
                    @error('position')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="hm">HM</label>
                    <input type="text" name="hm" value="{{ old('hm') }}" id="hm" class="form-control @error('hm') is-invalid @enderror">
                    @error('hm')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="rtd1">RTD1</label>
                    <input type="text" name="rtd1" value="{{ old('rtd1') }}" id="rtd1" class="form-control @error('rtd1') is-invalid @enderror">
                    @error('rtd1')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="rtd2">RTD2</label>
                    <input type="text" name="rtd2" value="{{ old('rtd2') }}" id="rtd2" class="form-control @error('rtd2') is-invalid @enderror">
                    @error('rtd2')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            {{-- <div class="col-4">
                <div class="form-group">
                    <label for="removal_reason_id">Removal Reason</label>
                    <select name="removal_reason_id" class="form-control select2bs4 @error('removal_reason_id') is-invalid @enderror">
                        <option value="">-- select Removal Reason --</option>
                        @foreach ($removal_reasons as $reason)
                        <option value="{{ $reason->id }}" {{ $reason->id == old('removal_reason_id') ? "selected" : "" }}>{{ $reason->description }}</option>
                        @endforeach
                    </select>
                    @error('removal_reason')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div> --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="remark">Remarks</label>
                    <input type="text" name="remark" value="{{ old('remark') }}" id="remark" class="form-control @error('remark') is-invalid @enderror">
                    @error('remark')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>

        </div> <!-- /.modal-body -->
        <div class="modal-footer float-left">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"> Close</button>
            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Save</button>
        </div>
    </form>
    </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>