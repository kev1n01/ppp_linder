<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ItemOverviewWidget;
use App\Filament\Widgets\SaleOverviewWidget;
use App\Filament\Widgets\SalesChartByDay;
use App\Filament\Widgets\SalesCountItemByType;
use Filament\Pages\Page;

class DashboardAdmin extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard-admin';

    protected static ?string $navigationLabel = 'Dashboard';  

    public function getTitle(): string
    {
        return 'Bienvenido ' . auth()->user()->name;
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    protected function getHeaderWidgets(): array
    {
        return [
          ItemOverviewWidget::class,
          SaleOverviewWidget::class,
          SalesChartByDay::class,
          SalesCountItemByType::class,
        ];
    }
}
