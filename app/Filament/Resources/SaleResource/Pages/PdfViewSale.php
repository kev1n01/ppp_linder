<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Models\Sale;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class PdfViewSale extends Page
{
    protected static string $resource = SaleResource::class;

    protected static string $view = 'filament.resources.sale-resource.pages.pdf-view-sale';

    public $record;
    public $info;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Imprimir')
              ->icon('heroicon-o-printer')
              ->url(
                fn(): string => route('download.pdf.venta', $this->record),
                shouldOpenInNewTab: false
              )
              ->requiresConfirmation(),
        ];
    }

    public function mount(Sale $record)
    {
      $this->record = $record;
      $this->info = Setting::first();
    }
    

}
