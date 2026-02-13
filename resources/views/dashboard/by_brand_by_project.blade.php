<div class="card card-info">
    <div class="card-header py-1">
        <h3 class="card-title">Rekap Data by Brand by Project</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm">
            <thead>
                <tr>
                    <td rowspan="2" class="align-middle border-bottom">
                        Brands</td>
                    @foreach ($projects as $project)
                        <th class="text-center" style="border-bottom: 1px solid #000; border-right: 1px solid #000;"
                            colspan="3">{{ $project }}</th>
                    @endforeach
                </tr>
                <tr class="border-bottom border-dark" style="border-width: 2px !important;">
                    @foreach ($projects as $project)
                        <td class="text-right"><small>Active</small></td>
                        <td class="text-right"><small>Inactive</small></td>
                        <td class="text-right" style="border-right: 1px solid #000;"><small>Avg CPH</small></td>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($by_brands_by_project as $brand)
                    <tr>
                        <td class="border-right">{{ $brand['brand'] }}</td>
                        @foreach ($projects as $project)
                            @php
                                $project_data = $brand['projects'][$project] ?? null;
                            @endphp
                            <td class="text-right">
                                <small>{{ $project_data ? $project_data['active_tyres'] : 0 }}</small>
                            </td>
                            <td class="text-right">
                                <small>{{ $project_data ? $project_data['inactive_tyres'] : 0 }}</small>
                            </td>
                            <td class="text-right" style="border-right: 1px solid #000;">
                                <small>{{ $project_data ? $project_data['average_cph'] : '-' }}</small>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
