<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Data ID</th>
            <th>serial_number</th>
            <th>is_new</th>
            <th>brand</th>
            <th>production_year</th>
            <th>size</th>
            <th>pattern</th>
            <th>po_no</th>
            <th>do_no</th>
            <th>do_date</th>
            <th>otd</th>
            <th>last_rtd1</th>
            <th>last_rtd2</th>
            <th>pressure</th>
            <th>vendor</th>
            <th>receive_date</th>
            <th>current_project</th>
            <th>price</th>
            <th>hours_target</th>
            <th>accumulated_hm</th>
            <th>waranty_exp_date</th>
            <th>status</th>
            <th>created_by</th>
            <th>created_at</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tyres as $key => $tyre)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $tyre->id }}</td>
                <td>{{ $tyre->serial_number }}</td>
                <td>{{ $tyre->is_new && $tyre->is_new == 1 ? 'new' : 'used' }}</td>
                <td>{{ $tyre->brand->name }}</td>
                <td>{{ $tyre->prod_year }}</td>
                <td>{{ $tyre->size->description }}</td>
                <td>{{ $tyre->pattern->name }}</td>
                <td>{{ $tyre->po_no }}</td>
                <td>{{ $tyre->do_no }}</td>
                <td>{{ $tyre->do_date ? date('d-M-Y', strtotime($tyre->do_date)) : 'n/a' }}</td>
                <td>{{ $tyre->otd }}</td>
                <td>{{ $tyre->getLastTransaction() ? $tyre->getLastTransaction()->rtd1 : "n/a" }}</td>
                <td>{{ $tyre->getLastTransaction() ? $tyre->getLastTransaction()->rtd2 : "n/a" }}</td>
                <td>{{ $tyre->pressure }}</td>
                <td>{{ $tyre->supplier->name }}</td>
                <td>{{ $tyre->receive_date ? date('d-M-Y', strtotime($tyre->receive_date)) : 'n/a' }}</td>
                <td>{{ $tyre->current_project }}</td>
                <td>{{ $tyre->price }}</td>
                <td>{{ $tyre->hours_target }}</td>
                <td>{{ $tyre->accumulated_hm }}</td>
                <td>{{ $tyre->waranty_exp_date ? date('d-M-Y', strtotime($tyre->waranty_exp_date)) : 'n/a' }}</td>
                <td>
                    @if($tyre->is_active == 1)
                        Active
                    @else
                        Inactive
                    @endif
                </td>
                <td>{{ $tyre->user->name }}</td>
                <td>{{ $tyre->created_at ? date('d-M-Y', strtotime($tyre->created_at)) : 'n/a' }}</td>
        @endforeach
    </tbody>
</table>
