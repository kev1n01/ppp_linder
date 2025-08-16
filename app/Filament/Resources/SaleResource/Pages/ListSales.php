<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getTableQuery(): Builder
    {
        $user = auth()->user();
        if ($user->hasRole('empleado')) {
          return static::getResource()::getEloquentQuery()->where('user_id',  $user->id);
        }else{
          return static::getResource()::getEloquentQuery();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
