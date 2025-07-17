<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
          Stat::make('Total de clientes', Customer::count())
            ->color('success'),
          Stat::make('Total de empleados', Employee::count())
            ->color('success'),
          Stat::make('Total de ventas', Sale::count())
            ->color('success'),
          Stat::make('Total de servicios', Sale::count())
            ->color('success'),
        ];
    }
}
