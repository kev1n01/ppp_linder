<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use App\Models\SaleDetail;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ItemOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {   
        $lowStockCount = Item::where('ite_type', 'producto')->where('ite_stock', '<=', '5')->count();

        // top product sale
        $topProduct = $this->getTopItemByType('producto');
        $topService = $this->getTopItemByType('servicio');

        return [
            Stat::make('Productos bajo stock', $lowStockCount)
              ->description('Stock igual o menor a 5')
              ->color('danger')
              ->icon('heroicon-o-exclamation-circle'),
            Stat::make('Producto más vendido', $topProduct?->ite_name ?? 'N/A')
              ->description(
                $topProduct
                    ? "Con {$topProduct?->total_sales} venta(s)"
                    : 'No se han registrado ventas'
              )
              ->icon('heroicon-o-bolt')
              ->color('success'),
            Stat::make('Servicio más vendido', $topService?->ite_name ?? 'N/A')
              ->description(
                $topService
                    ? "Con {$topService->total_sales} venta(s)"
                    : 'No se han registrado ventas'
              )
              ->icon('heroicon-o-wrench-screwdriver')
              ->color('info'),
        ];
    }

    function getTopItemByType(string $type): ?Item
    {
        // Obtener el ID del ítem más vendido de ese tipo
        $topItemId = SaleDetail::select('item_id', DB::raw('COUNT(*) as total'))
            ->join('items', 'items.id', '=', 'sale_details.item_id')
            ->where('items.ite_type', $type)
            ->groupBy('item_id')
            ->orderByDesc('total')
            ->pluck('item_id')
            ->first();

        if (!$topItemId) {
            return null;
        }

        // Obtener el ítem con su total de ventas
        $item = Item::find($topItemId);
        $item->total_sales = SaleDetail::where('item_id', $topItemId)->count();

        return $item;
    }
}
