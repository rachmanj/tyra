<div class="card card-info">
    <div class="card-header py-1">
        <h3 class="card-title">Rekap Data by Brand</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Brand</th>
                    <th class="text-right">Active Tyres</th>
                    <th class="text-right">Inactive Tyres</th>
                    <th class="text-right">Average CPH</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($by_brands as $values)
                    <tr class="py-0">
                        <td class="py-0"><small><b>{{ $values['brand'] }}</b></small></td>
                        <td class="text-right py-0"><small>{{ $values['active_tyres'] }}</small></td>
                        <td class="text-right py-0"><small>{{ $values['inactive_tyres'] }}</small></td>
                        <td class="text-right py-0"><small>Rp. {{ $values['average_cph'] }}</small></td>
                    </tr>
                @endforeach
                <tr>
                    <td><small><b>TOTAL</b></small></td>
                    <td class="text-right">
                        <small><b>{{ array_sum(array_column($by_brands, 'active_tyres')) }}</b></small>
                    </td>
                    <td class="text-right">
                        <small><b>{{ array_sum(array_column($by_brands, 'inactive_tyres')) }}</b></small>
                    <td class="text-right"><small>-</small></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
