<div class="card card-info">
    <div class="card-header py-1">
        <h3 class="card-title">Rekap Data</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Description</th>
                    @foreach ($data['data'] as $values)
                        <th class="text-right">{{ $values['project'] }}</th>
                    @endforeach
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><small>Active Tyres count</small></td>
                    @foreach ($data['data'] as $values)
                        <td class="text-right"><small>{{ $values['active_tyres'] }}</small></td>
                    @endforeach
                    <td class="text-right"><small>{{ array_sum(array_column($data['data'], 'active_tyres')) }}</small>
                    </td>
                </tr>
                <tr>
                    <td><small>In-Active Tyres count</small></td>
                    @foreach ($data['data'] as $values)
                        <td class="text-right"><small>{{ $values['inactive_tyres'] }}</small></td>
                    @endforeach
                    <td class="text-right"><small>{{ array_sum(array_column($data['data'], 'inactive_tyres')) }}</small>
                    </td>
                </tr>
                <tr>
                    <td><small>AVG CPH Active Tyres</small></td>
                    @foreach ($data['data'] as $values)
                        <td class="text-right"><small>Rp. {{ $values['active_average_cph'] }}</small></td>
                    @endforeach
                    <td class="text-right"><small>Rp. {{ $data['total_active']['total_active_average_cph'] }}</small>
                    </td>
                </tr>
                <tr>
                    <td><small>AVG CPH In-Active Tyres</small></td>
                    @foreach ($data['data'] as $values)
                        <td class="text-right"><small>Rp. {{ $values['inactive_average_cph'] }}</small></td>
                    @endforeach
                    <td class="text-right"><small>Rp.
                            {{ $data['total_inactive']['total_inactive_average_cph'] }}</small>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
