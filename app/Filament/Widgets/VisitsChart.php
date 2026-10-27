<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Visit;
use Carbon\Carbon;

class VisitsChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Kunjungan';
    protected static ?int $sort = 3;
    public ?string $filter = 'week';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => '7 Hari Terakhir',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $query = Visit::query();
        $labels = collect();
        $counts = collect();

        if ($activeFilter === 'today') {
            $data = $query->whereDate('created_at', Carbon::today())
                ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                ->groupBy('hour')
                ->pluck('count', 'hour');

            for ($i = 6; $i <= 18; $i++) {
                $labels->push(str_pad($i, 2, '0', STR_PAD_LEFT) . ':00');
                $counts->push($data->get($i) ?? 0);
            }
        } elseif ($activeFilter === 'week') {
            $data = $query->where('created_at', '>=', now()->subDays(6))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $labels->push(Carbon::parse($date)->translatedFormat('D, d M'));
                $counts->push($data->get($date) ?? 0);
            }
        } elseif ($activeFilter === 'month') {
            $data = $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $daysInMonth = now()->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = now()->format('Y-m-') . str_pad($i, 2, '0', STR_PAD_LEFT);
                $labels->push($i);
                $counts->push($data->get($date) ?? 0);
            }
        } elseif ($activeFilter === 'year') {
            $data = $query->whereYear('created_at', now()->year)
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->pluck('count', 'month');

            for ($i = 1; $i <= 12; $i++) {
                $labels->push(Carbon::create()->month($i)->translatedFormat('M'));
                $counts->push($data->get($i) ?? 0);
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Kunjungan',
                    'data' => $counts->toArray(),
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
