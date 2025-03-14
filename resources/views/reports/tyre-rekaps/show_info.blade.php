<dl class="row">
    <dt class="col-sm-4">Serial No</dt>
    <dd class="col-sm-8">: <b>{{ $tyre->serial_number }}</b></dd>
    <dt class="col-sm-4">Size | Pattern | Pressure</dt>
    <dd class="col-sm-8">: {{ $tyre->size->description }} | {{ $tyre->pattern->name }} | {{ $tyre->pressure }}</dd>
    <dt class="col-sm-4">Brand</dt>
    <dd class="col-sm-8">: {{ $tyre->brand->name }}</dd>
    <dt class="col-sm-4">PO No</dt>
    <dd class="col-sm-8">: {{ $tyre->po_no }}</dd>
    <dt class="col-sm-4">DO</dt>
    <dd class="col-sm-8">: No.{{ $tyre->do_no }} | tgl.{{ date('d-M-Y', strtotime($tyre->do_date)) }}</dd>
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
    <dt class="col-sm-4">Warranty Expire Date</dt>
    <dd class="col-sm-8">: {{ date('d-M-Y', strtotime($tyre->waranty_exp_date)) }}</dd>
    <dt class="col-sm-4">Created by</dt>
    <dd class="col-sm-8">: {{ $tyre->user->name }}, at {{ $tyre->created_at->addHours(8)->format('d M Y - H:i:s') }}
        wita</dd>
</dl>
