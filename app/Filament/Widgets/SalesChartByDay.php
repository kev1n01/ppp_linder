<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesChartByDay extends ChartWidget
{
    protected static ?string $heading = 'Ventas por Día';

    protected int | string | array $columnSpan = 'full'; 

    protected static ?int $sort = 3;

    protected function getData(): array
    {
      // Obtener ventas agrupadas por fecha
      $sales = Sale::select(DB::raw('DATE(sal_date) as date'), DB::raw('SUM(sal_total_amount) as total'))
          ->groupBy('date')
          ->orderBy('date', 'asc')
          ->get();

      $labels = $sales->pluck('date')->map(function ($date) {
          return \Carbon\Carbon::parse($date)->format('d/m');
      })->toArray();

      $totals = $sales->pluck('total')->map(fn ($value) => round($value, 2))->toArray();

      return [
          'datasets' => [
              [
                  'label' => 'Total Vendido (S/) por día',
                  'data' => $totals,
                  'borderColor' => '#3b82f6', 
                  'backgroundColor' => 'rgba(59,130,246,0.2)',
                  'fill' => true,
                  'tension' => 0.3,
              ],
          ],
          'labels' => $labels,
      ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
