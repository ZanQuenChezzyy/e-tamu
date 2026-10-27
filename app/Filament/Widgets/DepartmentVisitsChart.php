<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Visit;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentVisitsChart extends ChartWidget
{
    protected ?string $heading = 'Tujuan Ruangan Terpopuler';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $data = Visit::select('department_id', DB::raw('count(*) as total'))
            ->groupBy('department_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('department')
            ->get();

        $labels = $data->map(fn($item) => $item->department ? $item->department->name : 'Lainnya')->toArray();
        $counts = $data->map(fn($item) => $item->total)->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Kunjungan',
                    'data' => $counts,
                    'backgroundColor' => [
                        '#3b82f6', // blue-500
                        '#10b981', // emerald-500
                        '#f59e0b', // amber-500
                        '#ef4444', // red-500
                        '#8b5cf6', // violet-500
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
