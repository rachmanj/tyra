<dl class="row">
    <dt class="col-sm-4">Serial No</dt>
    <dd class="col-sm-8">: <b>{{ $tyre->serial_number }}</b>
        @if ($tyre->is_new)
            <span class="badge badge-success">New</span>
        @else
            <span class="badge badge-danger">Used</span>
        @endif
    </dd>
    <dt class="col-sm-4">Size | Pattern | Pressure</dt>
    <dd class="col-sm-8">: {{ $tyre->size->description }} | {{ $tyre->pattern->name }} | {{ $tyre->pressure }}</dd>
    <dt class="col-sm-4">Brand | Production Year</dt>
    <dd class="col-sm-8">: {{ $tyre->brand->name }} | {{ $tyre->prod_year ? $tyre->prod_year : ' - ' }}</dd>
    <dt class="col-sm-4">PO No</dt>
    <dd class="col-sm-8">: {{ $tyre->po_no }}</dd>
    <dt class="col-sm-4">DO</dt>
    <dd class="col-sm-8">: No.{{ $tyre->do_no }}</dd>
    <dt class="col-sm-4">Received Date</dt>
    <dd class="col-sm-8">: {{ $tyre->receive_date ? date('d-M-Y', strtotime($tyre->receive_date)) : ' - ' }}</dd>
    <dt class="col-sm-4">Vendor</dt>
    <dd class="col-sm-8">: {{ $tyre->supplier->name }}</dd>
    <dt class="col-sm-4">Price</dt>
    <dd class="col-sm-8">: IDR {{ number_format($tyre->price, 0, ',', '.') }}</dd>
    <dt class="col-sm-4">Hours | CPH (Target)</dt>
    <dd class="col-sm-8">: {{ $tyre->hours_target }} | IDR
        {{ $tyre->price && $tyre->hours_target > 0 ? number_format($tyre->price / $tyre->hours_target, 0, ',', '.') : '-' }}
    </dd>
    <dt class="col-sm-4">Hours | CPH (Realization)</dt>
    <dd class="col-sm-8">: {{ $tyre->accumulated_hm }} | IDR
        {{ $tyre->price && $tyre->accumulated_hm > 0 ? number_format($tyre->price / $tyre->accumulated_hm, 0, ',', '.') : '-' }}
    </dd>
    <dt class="col-sm-4">Project</dt>
    <dd class="col-sm-8">: {{ $tyre->current_project }}</dd>
    @if (!$tyre->is_active && $tyre->inactive_reason)
        <dt class="col-sm-4">Inactive Reason</dt>
        <dd class="col-sm-8">: <span class="badge badge-warning">{{ $tyre->inactive_reason }}</span>
            @if ($tyre->inactive_date)
                <small class="text-muted">({{ date('d-M-Y H:i', strtotime($tyre->inactive_date)) }})</small>
            @endif
        </dd>
        @if ($tyre->inactive_notes)
            <dt class="col-sm-4">Inactive Notes</dt>
            <dd class="col-sm-8">: {{ $tyre->inactive_notes }}</dd>
        @endif
    @endif
    <dt class="col-sm-4">Warranty Expire Date</dt>
    <dd class="col-sm-8">: {{ $tyre->warranty_exp_date ? date('d-M-Y', strtotime($tyre->warranty_exp_date)) : '-' }}
    </dd>
    <dt class="col-sm-4">Warranty Expire HM</dt>
    <dd class="col-sm-8">: {{ $tyre->warranty_exp_hm ? number_format($tyre->warranty_exp_hm, 0, ',', '.') : '-' }}</dd>
    <dt class="col-sm-4">Created by</dt>
    <dd class="col-sm-8">: {{ $tyre->user->name }}, at {{ $tyre->created_at->addHours(8)->format('d M Y - H:i:s') }}
        wita</dd>
</dl>
