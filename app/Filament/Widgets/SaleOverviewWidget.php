<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SaleOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $today = now()->startOfDay()->toDateString();
        $yesterday = now()->subDay()->startOfDay()->toDateString();
        $thisMonth = now()->startOfMonth()->toDateString();
        $lastMonth = now()->subMonth()->startOfMonth()->toDateString();

        // Today's revenue sale 
        $todayRevenue = Sale::forDateRange($today, now())->sum('sal_total_amount');
        $yesterdayRevenue = Sale::forDateRange($yesterday, $today)->sum('sal_total_amount');

        // Month's revenue sale 
        $thisMonthRevenue = Sale::forDateRange($thisMonth, now())->sum('sal_total_amount');
        $lastMonthRevenue = Sale::forDateRange($lastMonth, $thisMonth)->sum('sal_total_amount');

        // Total sale today
        $todaySaleCount = Sale::forDateRange($today, now())->count();

        return [
          Stat::make('Ganancias de hoy', 'S/.' . number_format($todayRevenue, 2))
              ->description($this->getPercentageChange($todayRevenue, $yesterdayRevenue) . ' desde ayer')
              ->descriptionIcon('heroicon-m-arrow-trending-up')
              ->color($todayRevenue >= $yesterdayRevenue ? 'success' : 'danger'),
          Stat::make('Ganancia mensual', 'S/.' . number_format($thisMonthRevenue, 2))
              ->description($this->getPercentageChange($thisMonthRevenue, $lastMonthRevenue) . ' desde el último mes')
              ->descriptionIcon('heroicon-m-calendar-days')
              ->color($thisMonthRevenue >= $lastMonthRevenue ? 'success' : 'warning'),
          Stat::make('Ventas de hoy', number_format($todaySaleCount))
              ->description('Ventas hechas')
              ->descriptionIcon('heroicon-m-check-circle')
              ->color('info'),
        ];
    }

    private function getPercentageChange(float $current, float $previous): string
    {
      if($previous == 0) return $current > 0 ? '+100%' : '0%';

      $percentage = (($current - $previous) / $previous) * 100;
      return ($percentage >= 0 ? '+' : '') . number_format($percentage, 1) . '%';
    }
}
