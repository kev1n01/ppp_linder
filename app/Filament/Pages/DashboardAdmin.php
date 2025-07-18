<?php

namespace App\Filament\Pages;

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
}
