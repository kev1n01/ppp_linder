<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        // if (!$user->hasRole('empleado')) {
        //   Notification::make()
        //   ->title('Solo los empleados pueden realizar una venta')
        //   ->danger()
        //   ->send();
          
        //   $this->halt(); 
        // }
        $data['user_id'] = auth()->user()->id;
        $data['uuid'] = Str::uuid();
        $data['customer_id'] = intval($data['customer_id']);
        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->record->saleDetails as $detail) {
          $item = $detail->item; 
  
          if ($item && $item->ite_stock !== null && $item->ite_type === 'producto') {
              $item->decrement('ite_stock', $detail->sald_quantity);
          }
        }
    }
}
