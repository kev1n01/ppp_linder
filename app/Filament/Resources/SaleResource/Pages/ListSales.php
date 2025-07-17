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
        $query = parent::getTableQuery();

        $user = auth()->user();

        if ($user->hasRole('empleado')) {
            // Filtrar por empleado_id asociado al usuario
            $query->where('employee_id', $user->employee->id ?? null);
        }

        // Si el usuario es admin, no filtramos (muestra todo)
        return $query;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
