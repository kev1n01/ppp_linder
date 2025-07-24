<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
// use App\Models\SaleDetail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
    
  //   protected function beforeSave(): void
  //   {
  //   dd($this->record, $this->record->saleDetails);

  //   $venta = $this->record;

  //   // Obtener los detalles antiguos desde BD
  //   $originalDetails = SaleDetail::where('sale_id', $venta->id)->get()->keyBy('id');

  //   foreach ($venta->saleDetails as $detail) {
  //       $original = $originalDetails->get($detail->id);

  //       if (!$detail->item || $detail->item->ite_type !== 'producto') {
  //           continue;
  //       }

  //       $item = $detail->item;

  //       // 1. Si existe original, resta el original y aplica el nuevo
  //       if ($original) {
  //           $cantidadOriginal = $original->sald_quantity;
  //           $cantidadNueva = $detail->sald_quantity;

  //           $diferencia = $cantidadOriginal - $cantidadNueva;

  //           // Si diferencia > 0, se devuelve stock. Si < 0, se descuenta más.
  //           $item->increment('ite_stock', $diferencia);
  //       } else {
  //           // 2. Si es un nuevo detalle, descuenta stock
  //           $item->decrement('ite_stock', $detail->sald_quantity);
  //       }
  //   }

  //   // 3. Detectar si se eliminaron detalles
  //   $nuevosIds = collect($venta->saleDetails)->pluck('id')->filter()->all();
  //   $eliminados = $originalDetails->except($nuevosIds);

  //   foreach ($eliminados as $eliminado) {
  //       if ($eliminado->item && $eliminado->item->ite_type === 'producto') {
  //           $eliminado->item->increment('ite_stock', $eliminado->sald_quantity);
  //       }
  //   }
  // }
}
