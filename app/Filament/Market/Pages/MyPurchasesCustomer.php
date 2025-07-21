<?php

namespace App\Filament\Market\Pages;

use App\Models\Sale;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;

class MyPurchasesCustomer extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static string $view = 'filament.pages.my-purchases-customer';

    protected static ?string $navigationLabel = 'Mis compras';  

    protected static ?string $title = 'Lista de mis compras'; 
    
    protected static ?string $slug = 'mis-compras';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('cliente');
    }

    public static function getNavigationBadge(): ?string
    {
      return Sale::where('customer_id', auth()->user()->customer->id)->count();
    }

    public static function table(Table $table): Table
    {
      return $table
            ->query(Sale::where('customer_id', auth()->user()->customer->id))
            ->columns([
              \Filament\Tables\Columns\TextColumn::make('id')
                  ->label('Nº compra'),
              \Filament\Tables\Columns\TextColumn::make('sal_total_amount')
                  ->label('Total')
                  ->prefix('S/. ')
                  ->money('PEN')
                  ->numeric(),
              \Filament\Tables\Columns\TextColumn::make('sal_payment_method')
                  ->label('Metodo pago')
                  ->badge()
                  ->color(fn (string $state): string => match ($state){
                    'efectivo' => 'danger',
                    'tarjeta' => 'warning',
                  }), 
              \Filament\Tables\Columns\TextColumn::make('sal_date')
                  ->label('Fecha compra')
                  ->date('d-m-Y')
                  ->sortable(),
              \Filament\Tables\Columns\TextColumn::make('updated_at')
                  ->label('Actualizado el')
                  ->date('d-m-Y')
                  ->sortable(),
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('ver pdf')
                  ->icon('heroicon-o-document')
                  ->url(
                    fn($record): string => route('ver.pdf.venta', $record),
                    shouldOpenInNewTab: true
                  ),
                \Filament\Tables\Actions\Action::make('descargar')
                  ->icon('heroicon-o-arrow-down-on-square')
                  ->url(
                    fn($record): string => route('download.pdf.venta', $record),
                    shouldOpenInNewTab: true
                  ),
            ]);
    }
}