<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        if (!$user->hasRole('empleado')) {
          Notification::make()
          ->title('Solo los empleados pueden realizar una venta')
          ->danger()
          ->send();
          
          $this->halt(); 
        }
        $data['employee_id'] = auth()->user()->employee->id;
        $data['customer_id'] = intval($data['customer_id']);
        return $data;
    }
}
