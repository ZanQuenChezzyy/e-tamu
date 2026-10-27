<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Visit;
use Carbon\Carbon;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Sparkline for today's visits (by hour)
        $todayVisits = Visit::whereDate('created_at', Carbon::today())
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->pluck('count', 'hour');

        $todaySparkline = [];
        for ($i = 6; $i <= 18; $i++) {
            $todaySparkline[] = $todayVisits->get($i) ?? 0;
        }

        // Monthly sparkline (last 7 days)
        $monthlyVisits = Visit::where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $monthlySparkline = [];
        for ($i = 6; $i >= 0; $i--) {
            $monthlySparkline[] = $monthlyVisits->get(now()->subDays($i)->format('Y-m-d')) ?? 0;
        }

        return [
            Stat::make('Kunjungan Hari Ini', Visit::whereDate('created_at', Carbon::today())->count())
                ->description('Tamu yang hadir hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($todaySparkline)
                ->color('success'),

            Stat::make('Tamu Aktif (Di Lokasi)', Visit::where('status', 'completed')->whereNull('check_out_time')->count())
                ->description('Belum check-out')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Menunggu Persetujuan', Visit::where('status', 'pending')->count())
                ->description('Perlu tindakan segera')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Total Kunjungan (7 Hari)', Visit::where('created_at', '>=', now()->subDays(7))->count())
                ->description('Tren kunjungan seminggu terakhir')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($monthlySparkline)
                ->color('primary'),
        ];
    }
}
