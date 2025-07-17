<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Sale;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $modelLabel = 'Venta';
    
    protected static ?string $pluralModelLabel = 'Ventas';

    protected static ?string $navigationLabel = 'Ventas';  

    protected static ?string $navigationBadgeTooltip = 'Ventas';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
      return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Forms\Components\Section::make('Información de la Venta')
              ->schema([
                  Forms\Components\Select::make('customer_id')
                      ->label('Cliente')
                      ->relationship('customer', 'cu_name')
                      ->searchable()
                      ->preload()
                      ->required(),
                  Forms\Components\Select::make('sal_payment_method')
                      ->label('Método de pago')
                      ->options([
                          'efectivo' => 'Efectivo',
                          'tarjeta' => 'Tarjeta',
                      ])
                      ->required(),
                  Forms\Components\DatePicker::make('sal_date')
                      ->label('Fecha')
                      ->required(),

                  // Campo total (solo lectura)
                  Forms\Components\TextInput::make('sal_total_amount')
                      ->label('Total')
                      ->default('0.00')
                      ->numeric()
                      ->disabled()
                      ->dehydrated(),
              ])->columns(4),
              
              // Repeater para detalles
              Forms\Components\Section::make('Detalles de la Venta')
              ->schema([
                Forms\Components\Repeater::make('sale_details')
                ->label('Detalles de la venta')
                ->relationship() 
                ->schema([
                  Forms\Components\Select::make('service_id')
                        ->label('Servicio')
                        ->options(Service::all()->pluck('ser_name', 'id')) 
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $service = \App\Models\Service::find($state);
                            if ($service) {
                                $set('sald_price', $service->ser_price);
                                $set('sald_quantity', 1);
                                $set('sald_subtotal', $service->ser_price);
                            }
                        })
                        ->required(),
  
                  Forms\Components\TextInput::make('sald_quantity')
                        ->numeric()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $price = $get('sald_price') ?? 0;
                            $quantity = $state ?? 1;
                            $subtotal = $price * $quantity;
                            $set('sald_subtotal', $subtotal);
                          })
                        ->required(),
  
                  Forms\Components\TextInput::make('sald_price')
                        ->numeric()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $price = $state ?? 0;
                            $quantity = $get('sald_quantity') ?? 1;
                            $subtotal = $price * $quantity;
                            $set('sald_subtotal', $subtotal);
                        })
                        ->required(),
  
                  Forms\Components\TextInput::make('sald_subtotal')
                        ->numeric()
                        ->disabled()
                        ->required(),
                ])
                ->live()
                ->afterStateUpdated(function ($state, callable $set) {
                    $total = 0;
                    if (is_array($state)) {
                      foreach ($state as $item) {
                        $total += (float)($item['sald_subtotal'] ?? 0);
                      }
                    }
                    // dd($total);
                    $set('sal_total_amount', number_format($total, 2, '.', ''));
                })
                ->addActionLabel('Agregar')
                ->columns(4),
              ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sal_total_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sal_payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sal_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
