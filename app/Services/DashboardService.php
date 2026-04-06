<?php

namespace App\Services;

use App\Models\Tyre;
use App\Models\TyreBrand;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public const DASHBOARD_PROJECTS = ['017C', '021C', '022C', '023C', '025C'];

    public const CACHE_TTL_MINUTES = 10;

    public function getActiveTyreCount(?array $projects = null): int
    {
        return Tyre::where('is_active', 1)
            ->when($projects, fn ($q) => $q->whereIn('current_project', $projects))
            ->count();
    }

    public function getAvgCphActive(?array $projects = null): string
    {
        return $this->formatCph(
            Tyre::where('is_active', 1)
                ->when($projects, fn ($q) => $q->whereIn('current_project', $projects))
                ->selectRaw('SUM(accumulated_hm) as total_hm, SUM(price) as total_price')
                ->first()
        );
    }

    public function getAvgCphInactive(?array $projects = null): string
    {
        return $this->formatCph(
            Tyre::where('is_active', 0)
                ->when($projects, fn ($q) => $q->whereIn('current_project', $projects))
                ->selectRaw('SUM(accumulated_hm) as total_hm, SUM(price) as total_price')
                ->first()
        );
    }

    public function getInactiveTyreCount(?array $projects = null): int
    {
        return Tyre::where('is_active', 0)
            ->when($projects, fn ($q) => $q->whereIn('current_project', $projects))
            ->count();
    }

    public function getActiveTyreByProject(?array $projects = null): Collection
    {
        return Tyre::where('is_active', 1)
            ->when($projects, fn ($q) => $q->whereIn('current_project', $projects))
            ->selectRaw('count(*) as total, current_project')
            ->groupBy('current_project')
            ->get();
    }

    public function getDashboardData(?array $projects = null): array
    {
        $projects = $projects ?? $this->getDashboardProjects();
        $cacheKey = 'dashboard.data.' . md5(json_encode($projects));

        return Cache::remember($cacheKey, now()->addMinutes(self::CACHE_TTL_MINUTES), function () use ($projects) {
            return [
                'active_tyre_count' => $this->getActiveTyreCount($projects),
                'inactive_tyre_count' => $this->getInactiveTyreCount($projects),
                'avg_active' => $this->getAvgCphActive($projects),
                'avg_inactive' => $this->getAvgCphInactive($projects),
                'active_tyre_by_project' => $this->getActiveTyreByProject($projects),
                'data' => $this->getRekapData($projects),
                'by_brands' => $this->getRekapDataByBrand($projects),
                'by_brands_by_project' => $this->getRekapDataByBrandByProject($projects),
            ];
        });
    }

    public function getDashboardProjects(): array
    {
        return config('tyra.dashboard_projects', self::DASHBOARD_PROJECTS);
    }

    public static function clearDashboardCache(): void
    {
        $allProjects = config('tyra.dashboard_projects', self::DASHBOARD_PROJECTS);
        Cache::forget('dashboard.data.' . md5(json_encode($allProjects)));
        foreach ($allProjects as $project) {
            Cache::forget('dashboard.data.' . md5(json_encode([$project])));
        }
    }

    public function getRekapData(?array $projects = null): array
    {
        $projects = $projects ?? $this->getDashboardProjects();
        $projectAggregates = $this->getProjectAggregates($projects);

        $data = [];
        $totalActiveHm = 0;
        $totalActivePrice = 0;
        $totalInactiveHm = 0;
        $totalInactivePrice = 0;

        foreach ($projects as $project) {
            $agg = $projectAggregates[$project] ?? null;
            if (!$agg) {
                $data[] = [
                    'project' => $project,
                    'active_tyres' => 0,
                    'inactive_tyres' => 0,
                    'average_cph' => '-',
                    'active_average_cph' => '-',
                    'inactive_average_cph' => '-',
                ];
                continue;
            }

            $totalActiveHm += $agg['active_hm'];
            $totalActivePrice += $agg['active_price'];
            $totalInactiveHm += $agg['inactive_hm'];
            $totalInactivePrice += $agg['inactive_price'];

            $data[] = [
                'project' => $project,
                'active_tyres' => $agg['active_count'],
                'inactive_tyres' => $agg['inactive_count'],
                'average_cph' => $this->formatCphFromSums($agg['total_hm'], $agg['total_price']),
                'active_average_cph' => $this->formatCphFromSums($agg['active_hm'], $agg['active_price']),
                'inactive_average_cph' => $this->formatCphFromSums($agg['inactive_hm'], $agg['inactive_price']),
            ];
        }

        return [
            'data' => $data,
            'total_active' => [
                'total_active_hm' => $totalActiveHm,
                'total_active_price' => $totalActivePrice,
                'total_active_average_cph' => $this->formatCphFromSums($totalActiveHm, $totalActivePrice),
            ],
            'total_inactive' => [
                'total_inactive_hm' => $totalInactiveHm,
                'total_inactive_price' => $totalInactivePrice,
                'total_inactive_average_cph' => $this->formatCphFromSums($totalInactiveHm, $totalInactivePrice),
            ],
        ];
    }

    public function getRekapDataByBrand(?array $projects = null): array
    {
        $brands = TyreBrand::all();
        $brandAggregates = $this->getBrandAggregates($projects);

        $data = [];
        foreach ($brands as $brand) {
            $agg = $brandAggregates[$brand->id] ?? null;
            if (!$agg) {
                $data[] = [
                    'brand' => $brand->name,
                    'active_tyres' => 0,
                    'inactive_tyres' => 0,
                    'average_cph' => '-',
                    'active_average_cph' => '-',
                    'inactive_average_cph' => '-',
                ];
                continue;
            }

            $data[] = [
                'brand' => $brand->name,
                'active_tyres' => $agg['active_count'],
                'inactive_tyres' => $agg['inactive_count'],
                'average_cph' => $this->formatCphFromSums($agg['total_hm'], $agg['total_price']),
                'active_average_cph' => $this->formatCphFromSums($agg['active_hm'], $agg['active_price']),
                'inactive_average_cph' => $this->formatCphFromSums($agg['inactive_hm'], $agg['inactive_price']),
            ];
        }

        return $data;
    }

    public function getRekapDataByBrandByProject(?array $projects = null): array
    {
        $brands = TyreBrand::orderBy('name', 'asc')->get();
        $projects = $projects ?? $this->getDashboardProjects();
        $aggregates = $this->getBrandProjectAggregates($projects);

        $data = [];
        foreach ($brands as $brand) {
            $brandData = [
                'brand' => $brand->name,
                'projects' => [],
            ];

            foreach ($projects as $project) {
                $key = "{$brand->id}_{$project}";
                $agg = $aggregates[$key] ?? null;

                if (!$agg) {
                    $brandData['projects'][$project] = [
                        'active_tyres' => 0,
                        'inactive_tyres' => 0,
                        'average_cph' => '-',
                        'active_average_cph' => '-',
                        'inactive_average_cph' => '-',
                    ];
                    continue;
                }

                $brandData['projects'][$project] = [
                    'active_tyres' => $agg['active_count'],
                    'inactive_tyres' => $agg['inactive_count'],
                    'average_cph' => $this->formatCphFromSums($agg['total_hm'], $agg['total_price']),
                    'active_average_cph' => $this->formatCphFromSums($agg['active_hm'], $agg['active_price']),
                    'inactive_average_cph' => $this->formatCphFromSums($agg['inactive_hm'], $agg['inactive_price']),
                ];
            }

            $data[] = $brandData;
        }

        return $data;
    }

    private function getProjectAggregates(array $projects): array
    {
        $results = Tyre::whereIn('current_project', $projects)
            ->selectRaw('
                current_project,
                SUM(accumulated_hm) as total_hm,
                SUM(price) as total_price,
                SUM(CASE WHEN is_active = 1 THEN accumulated_hm ELSE 0 END) as active_hm,
                SUM(CASE WHEN is_active = 1 THEN price ELSE 0 END) as active_price,
                SUM(CASE WHEN is_active = 0 THEN accumulated_hm ELSE 0 END) as inactive_hm,
                SUM(CASE WHEN is_active = 0 THEN price ELSE 0 END) as inactive_price,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive_count
            ')
            ->groupBy('current_project')
            ->get()
            ->keyBy('current_project');

        return $results->map(fn ($row) => [
            'total_hm' => (float) $row->total_hm,
            'total_price' => (float) $row->total_price,
            'active_hm' => (float) $row->active_hm,
            'active_price' => (float) $row->active_price,
            'inactive_hm' => (float) $row->inactive_hm,
            'inactive_price' => (float) $row->inactive_price,
            'active_count' => (int) $row->active_count,
            'inactive_count' => (int) $row->inactive_count,
        ])->toArray();
    }

    private function getBrandAggregates(?array $projects = null): array
    {
        $results = Tyre::query()
            ->when($projects, fn ($q) => $q->whereIn('current_project', $projects))
            ->selectRaw('
                brand_id,
                SUM(accumulated_hm) as total_hm,
                SUM(price) as total_price,
                SUM(CASE WHEN is_active = 1 THEN accumulated_hm ELSE 0 END) as active_hm,
                SUM(CASE WHEN is_active = 1 THEN price ELSE 0 END) as active_price,
                SUM(CASE WHEN is_active = 0 THEN accumulated_hm ELSE 0 END) as inactive_hm,
                SUM(CASE WHEN is_active = 0 THEN price ELSE 0 END) as inactive_price,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive_count
            ')
            ->groupBy('brand_id')
            ->get()
            ->keyBy('brand_id');

        return $results->map(fn ($row) => [
            'total_hm' => (float) $row->total_hm,
            'total_price' => (float) $row->total_price,
            'active_hm' => (float) $row->active_hm,
            'active_price' => (float) $row->active_price,
            'inactive_hm' => (float) $row->inactive_hm,
            'inactive_price' => (float) $row->inactive_price,
            'active_count' => (int) $row->active_count,
            'inactive_count' => (int) $row->inactive_count,
        ])->toArray();
    }

    private function getBrandProjectAggregates(?array $projects = null): array
    {
        $projects = $projects ?? $this->getDashboardProjects();
        $results = Tyre::whereIn('current_project', $projects)
            ->whereNotNull('brand_id')
            ->selectRaw('
                brand_id,
                current_project,
                SUM(accumulated_hm) as total_hm,
                SUM(price) as total_price,
                SUM(CASE WHEN is_active = 1 THEN accumulated_hm ELSE 0 END) as active_hm,
                SUM(CASE WHEN is_active = 1 THEN price ELSE 0 END) as active_price,
                SUM(CASE WHEN is_active = 0 THEN accumulated_hm ELSE 0 END) as inactive_hm,
                SUM(CASE WHEN is_active = 0 THEN price ELSE 0 END) as inactive_price,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive_count
            ')
            ->groupBy('brand_id', 'current_project')
            ->get();

        $keyed = [];
        foreach ($results as $row) {
            $key = "{$row->brand_id}_{$row->current_project}";
            $keyed[$key] = [
                'total_hm' => (float) $row->total_hm,
                'total_price' => (float) $row->total_price,
                'active_hm' => (float) $row->active_hm,
                'active_price' => (float) $row->active_price,
                'inactive_hm' => (float) $row->inactive_hm,
                'inactive_price' => (float) $row->inactive_price,
                'active_count' => (int) $row->active_count,
                'inactive_count' => (int) $row->inactive_count,
            ];
        }

        return $keyed;
    }

    private function formatCph(?object $row): string
    {
        if (!$row || !$row->total_hm || !$row->total_price) {
            return '-';
        }

        return number_format($row->total_price / $row->total_hm, 2);
    }

    private function formatCphFromSums(float $hm, float $price): string
    {
        return ($hm && $price) ? number_format($price / $hm, 2) : '-';
    }
}
