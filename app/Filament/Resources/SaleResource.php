<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Employee;
use App\Models\Sale;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
      $user = auth()->user();

      if ($user->hasRole('empleado')) {
        return static::getModel()::where('employee_id', $user->employee->id)->count();
      }else{
        return static::getModel()::count();
      }
    }

    public static function updateTotals(Get $get, Set $set): void
    {
        // Retrieve all selected products and remove empty rows
        $selectedServices = collect($get('saleDetails'))->filter(fn($item) => !empty($item['service_id']) && !empty($item['sald_quantity']));
    
        // Retrieve prices for all selected products
        $prices = Service::find($selectedServices->pluck('service_id'))->pluck('ser_price', 'id');
    
        // Calculate subtotal based on the selected products and quantities
        $subtotal = $selectedServices->reduce(function ($subtotal, $service) use ($prices) {
            return $subtotal + ($prices[$service['service_id']] * $service['sald_quantity']);
        }, 0);
    
        // Update the state with the new values
        $set('sal_total_amount', number_format($subtotal, 2, '.', ''));
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
                      ->required()
                      ->columnSpan(2),
                  Forms\Components\Select::make('sal_payment_method')
                      ->label('Método de pago')
                      ->default('efectivo')
                      ->options([
                          'efectivo' => 'Efectivo',
                          'tarjeta' => 'Tarjeta',
                      ])
                      ->required(),
                  Forms\Components\DatePicker::make('sal_date')
                      ->label('Fecha')
                      ->default(now())
                      ->required(),

                  // Campo total (solo lectura)
                  Forms\Components\TextInput::make('sal_total_amount')
                      ->label('Total')
                      ->default('0.00')
                      ->reactive()
                      ->readOnly()
                      ->prefix('S/.')
              ])->columns(5),
              
              // Repeater para detalles
              Forms\Components\Section::make('Detalles de la Venta')
              ->schema([
                Forms\Components\Repeater::make('saleDetails')
                ->label('Detalles de la venta')
                ->relationship() 
                ->schema([
                  Forms\Components\Select::make('service_id') 
                        ->label('Servicio')
                        ->options(Service::where('ser_status', true)->pluck('ser_name', 'id')) 
                        ->disableOptionWhen(function ($value, $state, Get $get) {
                          return collect($get('../*.service_id'))
                              ->reject(fn($id) => $id == $state)
                              ->filter()
                              ->contains($value);
                        })
                        ->afterStateUpdated(function ($state, callable $set) {
                            $service = \App\Models\Service::find($state);
                            if ($service) {
                                $set('sald_price', $service->ser_price);
                                $set('sald_subtotal', $service->ser_price);
                            }
                        })
                        ->searchable()
                        ->preload()
                        ->required(),
  
                  Forms\Components\TextInput::make('sald_quantity')
                        ->label('Cantidad')
                        ->integer()
                        ->afterStateUpdated(function ($state, callable $set, callable $get){
                            $set('sald_subtotal', 0);
                            $set('sald_subtotal', $get('sald_price') * $state);
                        })
                        ->default(1)
                        ->required(),
  
                  Forms\Components\TextInput::make('sald_price')
                        ->label('Precio')
                        ->reactive()
                        ->readOnly(),
  
                  Forms\Components\TextInput::make('sald_subtotal')
                        ->label('Subtotal')
                        ->reactive()
                        ->readOnly()
                        ->prefix('S/.'),
                ])
                ->live()
                ->afterStateUpdated(function (Get $get, Set $set) {
                    self::updateTotals($get, $set);
                })
                ->deleteAction(
                  fn(Action $action) => $action->after(fn(Get $get, Set $set) => self::updateTotals($get, $set)),
                )
                ->addActionLabel('Agregar')
                ->reorderable(false)
                ->columns(4),
              ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.cu_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.user.name')
                    ->label('Empleado')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sal_total_amount')
                    ->label('Total')
                    ->prefix('S/. ')
                    ->money('PEN')
                    ->numeric(),
                Tables\Columns\TextColumn::make('sal_payment_method')
                    ->label('Metodo pago')
                    ->badge()
                    ->color(fn (string $state): string => match ($state){
                      'efectivo' => 'danger',
                      'tarjeta' => 'sucess',
                    }), 
                Tables\Columns\TextColumn::make('sal_date')
                    ->label('Fecha')
                    ->date('d-m-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
              Tables\Filters\SelectFilter::make('sal_payment_method')
              ->label('Metodo pago')
              ->options([
                  'efectivo' => 'Efectivo',
                  'tarjeta' => 'Tarjeta',
              ]),
              Tables\Filters\SelectFilter::make('employee_id')
              ->label('Empleado')
              ->searchable()
              ->options(Employee::all()->pluck('user.name', 'id')) 
            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->filtersTriggerAction(
              fn (TableAction $action) => $action
                  ->button()
                  ->label('Filtros'),
            )
            ->actions([
              Tables\Actions\ActionGroup::make([
                  Tables\Actions\Action::make('ver pdf')
                  ->icon('heroicon-o-document'),
                  Tables\Actions\Action::make('descargar')
                  ->icon('heroicon-o-arrow-down-on-square'),
                  Tables\Actions\EditAction::make(),
                  Tables\Actions\ViewAction::make(),
              ])
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
