<?php

namespace App\Filament\Pages;

use App\Models\Item;
use Filament\Pages\Page;

class ListItemsCustomer extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static string $view = 'filament.pages.list-items-customer';

    protected static ?string $navigationLabel = 'Items';  

    protected static ?string $title = ''; 
    
    protected static ?string $slug = 'items-grifo';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('cliente');
    }
}
