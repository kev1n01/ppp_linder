<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Actions\Action;

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
        // Obteniendo todos los items
        $selectedServices = collect($get('saleDetails'))->filter(fn($item) => !empty($item['item_id']) && !empty($item['sald_quantity']));
    
        // Obteniendo precios de los productos seleccionados
        $prices = Item::find($selectedServices->pluck('item_id'))->pluck('ite_price', 'id');
    
        // Calculando subtotal en base a precio y cantidad
        $subtotal = $selectedServices->reduce(function ($subtotal, $service) use ($prices) {
            return $subtotal + ($prices[$service['item_id']] * $service['sald_quantity']);
        }, 0);
    
        // Actualizar el estado del monto total de venta
        $set('sal_total_amount', number_format($subtotal, 2, '.', ''));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Forms\Components\Section::make('Información de la Venta')
              ->schema([
                  Forms\Components\Hidden::make('employee_id'),
                  Forms\Components\Hidden::make('uuid'),
                  Forms\Components\Select::make('customer_id')  
                      ->label('Cliente')
                      ->options(Customer::where('cu_status', true)->pluck('cu_name', 'id')) 
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
                      ->columnSpan([
                        'default' => 'full',
                        'sm' => 1,
                        '2xl' => 1,
                      ])
                      ->required(),
                  Forms\Components\DatePicker::make('sal_date')
                      ->label('Fecha')
                      ->placeholder('DD-MM-YYYY')
                      ->native(false)
                      ->displayFormat('d-m-Y')
                      ->default(now())
                      ->required(),

                  // Campo total (solo lectura)
                  Forms\Components\TextInput::make('sal_total_amount')
                      ->label('Total')
                      ->default('0.00')
                      ->reactive()
                      ->readOnly()
                      ->columnSpan([
                        'default' => '2',
                        'sm' => 2,
                        '2xl' => 1,
                      ])
                      ->prefix('S/.')
              ])->columns([
                'default' => 'full',
                'sm' => 1,
                'lg' => 5,
                '2xl' => 5,
              ]),
              
              // Repeater para detalles
              Forms\Components\Section::make('Detalles de la Venta')
              ->schema([
                Forms\Components\Repeater::make('saleDetails')
                ->label('Detalles de la venta')
                ->relationship() 
                ->schema([
                  Forms\Components\Select::make('item_id') 
                        ->label('Item')
                        ->options(Item::where('ite_status', true)->pluck('ite_name', 'id')) 
                        ->disableOptionWhen(function ($value, $state, Get $get) {
                          return collect($get('../*.item_id'))
                              ->reject(fn($id) => $id == $state)
                              ->filter()
                              ->contains($value);
                        })
                        ->afterStateUpdated(function ($state, callable $set) {
                            $item = Item::find($state);
                            if ($item) {
                                $set('sald_price', $item->ite_price);
                                $set('sald_subtotal', $item->ite_price);
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
                            $set('sald_subtotal', number_format($get('sald_price') * $state, 2, '.', ''));
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
                Tables\Columns\TextColumn::make('uuid')
                  ->label('Codigo')
                  ->formatStateUsing(fn ($state): string => getCodFromUUID($state))
                  ->searchable(),
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
                      'tarjeta' => 'warning',
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
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                  Tables\Actions\Action::make('ver pdf')
                  ->icon('heroicon-o-document')
                  ->url(
                    fn($record): string => route('ver.pdf.venta', $record),
                    shouldOpenInNewTab: true
                  ),
                  Tables\Actions\Action::make('descargar')
                  ->icon('heroicon-o-arrow-down-on-square')
                  ->url(
                    fn($record): string => route('download.pdf.venta', $record),
                    shouldOpenInNewTab: true
                  ),
                  // Tables\Actions\Action::make('pdf vista')
                  // ->icon('heroicon-o-document')
                  // ->url(fn($record) => self::getUrl("pdf", [ 'record' => $record])),
                  Tables\Actions\EditAction::make(),
                  Tables\Actions\ViewAction::make(),
              ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            // 'pdf' => Pages\PdfViewSale::route('/view/{record}/pdf')
        ];
    }
}
