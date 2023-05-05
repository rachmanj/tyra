<table>
    <thead>
        <tr>
            <th>#</th>
            <th>data_id</th>
            <th>serial_number</th>
            <th>date</th>
            <th>unit_no</th>
            <th>tx_type</th>
            <th>position</th>
            <th>hm</th>
            <th>rtd1</th>
            <th>rtd2</th>
            <th>reason</th>
            <th>remark</th>
            <th>created_by</th>
            <th>created_at</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $key => $transaction)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->tyre->serial_number }}</td>
                <td>{{ $transaction->date ? date('d-M-Y', strtotime($transaction->date)) : 'n/a' }}</td>
                <td>{{ $transaction->unit_no }}</td>
                <td>{{ $transaction->tx_type }}</td>
                <td>{{ $transaction->position }}</td>
                <td>{{ $transaction->hm }}</td>
                <td>{{ $transaction->rtd1 }}</td>
                <td>{{ $transaction->rtd2 }}</td>
                <td>{{ $transaction->removalReason->description }}</td>
                <td>{{ $transaction->remark }}</td>
                <td>{{ $transaction->createdBy->name }}</td>
                <td>{{ $transaction->created_at ? date('d-M-Y', strtotime($transaction->created_at)) : 'n/a' }}</td>
        @endforeach
    </tbody>
</table>
