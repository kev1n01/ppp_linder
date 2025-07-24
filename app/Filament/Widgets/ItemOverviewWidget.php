<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use App\Models\SaleDetail;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ItemOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {   
        $lowStockCount = Item::where('ite_type', 'producto')->where('ite_stock', '<=', '5')->count();

        // top product sale
        $topItemProduct = Item::withCount('saleDetails')
            ->where('ite_type', 'producto')
            ->orderByDesc('sale_details_count')
            ->first();
        $topItemService = Item::withCount('saleDetails')
            ->where('ite_type', 'servicio')
            ->orderByDesc('sale_details_count')
            ->first();
        return [
            Stat::make('Productos bajo stock', $lowStockCount)
              ->description('Stock igual o menor a 5')
              ->color($lowStockCount > 0 ? 'warning' : 'success')
              ->icon('heroicon-o-exclamation-circle'),
            Stat::make('Producto más vendido', $topItemProduct?->ite_name ?? 'N/A')
              ->description(
                $topItemProduct
                    ? "Con {$topItemProduct->sale_details_count} venta(s)"
                    : 'No se han registrado ventas'
              )
              ->icon('heroicon-o-bolt')
              ->color('success'),
            Stat::make('Servicio más vendido', $topItemService?->ite_name ?? 'N/A')
              ->description(
                $topItemService
                    ? "Con {$topItemService->sale_details_count} venta(s)"
                    : 'No se han registrado ventas'
              )
              ->icon('heroicon-o-wrench-screwdriver')
              ->color('info'),
        ];
    }
}
