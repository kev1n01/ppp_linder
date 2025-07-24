<?php

namespace App\Filament\Widgets;

use App\Models\SaleDetail;
use Filament\Widgets\ChartWidget;

class SalesCountItemByType extends ChartWidget
{
    protected static ?string $heading = 'Ventas por tipo de item';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $productCount = SaleDetail::whereHas('item', function ($query) {
            $query->where('ite_type', 'producto');
        })->count();

        $serviceCount = SaleDetail::whereHas('item', function ($query) {
            $query->where('ite_type', 'servicio');
        })->count();

        return [
            'datasets' => [
                [
                    'label' => 'Ventas por tipo',
                    'data' => [$productCount, $serviceCount],
                    'backgroundColor' => ['#ef4444', '#facc15'],
                ],
            ],
            'labels' => ['Producto', 'Servicio'],
        ];
    }
}
