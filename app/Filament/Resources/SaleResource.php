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
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
        return static::getModel()::where('user_id', $user->id)->count();
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
                  Forms\Components\Hidden::make('user_id'),
                  Forms\Components\Hidden::make('uuid'),
                  Forms\Components\Select::make('customer_id')  
                      ->label('Cliente')
                      ->searchable()
                      ->relationship('customer', 'cu_name')
                      ->preload()
                      ->required()
                      ->columnSpan(2)
                      ->createOptionAction(
                        fn (Action $action) => $action->modalWidth('3xl'),
                      )
                      ->createOptionForm([
                        Forms\Components\TextInput::make('cu_num_doc')
                          ->label('Nº de documento')
                          ->unique(ignoreRecord: true)
                          ->autofocus()
                          ->required()
                          ->maxLength(11)
                          ->minLength(8)
                          ->required()
                          ->suffixAction(
                            fn ($state, $livewire, $set) => Action::make('search')
                                ->icon('heroicon-m-magnifying-glass')
                                ->action(function () use ($state, $livewire, $set) {
                                    $livewire->validateOnly('data.cu_num_doc');
                                    if (blank($state)) {
                                        Notification::make()
                                            ->title('El N° de documento no puede estar vacío.')
                                            ->danger()
                                            ->send();
                                        return;
                                    }
      
                                    if (strlen($state) === 8) {
                                        $response = Http::withOptions([
                                          'verify' => false,
                                        ])->get(
                                            'https://dniruc.apisperu.com/api/v1/dni/' . $state . '?token=' . env('VITE_TOKEN_DNI_API')
                                        )->throw()->json();
      
                                        if (!$response['success']) {
                                            Notification::make()
                                                ->title($response['message'])
                                                ->warning()
                                                ->send();
                                        }
      
                                        if ($response['success']) {
                                            Notification::make()
                                                ->title('Datos del cliente encontrados.')
                                                ->success()
                                                ->send();
                                            $set('cu_name', $response['nombres'] . ' ' . $response['apellidoPaterno'] . ' ' . $response['apellidoMaterno'] ?? null);
                                            $set('cu_type_doc', 'dni');
                                        }
                                    }
      
                                    if (strlen($state) === 11) {
                                        $response = Http::withOptions([
                                          'verify' => false,
                                        ])->get(
                                            'https://dniruc.apisperu.com/api/v1/ruc/' . $state . '?token=' . env('VITE_TOKEN_DNI_API')
                                        )->throw()->json();
      
                                        if (count($response) <= 2) {
                                            Notification::make()
                                                ->title($response['message'] . ' o el número de RUC no se encuentra activo.')
                                                ->warning()
                                                ->send();
                                        }
      
                                        if (count($response) > 3) {
                                            Notification::make()
                                                ->title('Datos del cliente encontrados.')
                                                ->success()
                                                ->send();
                                            $set('cu_name', $response['razonSocial'] ?? null);
                                            $set('cu_address', $response['direccion'] ?? null);
                                            $set('cu_type_doc', 'ruc');
                                        }
                                    }
                                })
                        ),
                        Forms\Components\Select::make('cu_type_doc')
                            ->label('Tipo de documento')
                            ->required()
                            ->searchable()
                            ->options([
                                'dni' => 'DNI',
                                'ruc' => 'RUC',
                            ]),
                        Forms\Components\TextInput::make('cu_name')
                            ->unique(ignoreRecord: true)
                            ->label('Nombres')
                            ->required(),
                        Forms\Components\TextInput::make('cu_email')
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->label('Correo Electrónico'),
                        Forms\Components\TextInput::make('cu_address')
                            ->label('Dirección'),
                        Forms\Components\TextInput::make('cu_phone')
                            ->unique(ignoreRecord: true)
                            ->label('Celular')
                            ->tel(),
                        Forms\Components\Hidden::make('user_id'),
                        Forms\Components\Toggle::make('cu_status')
                          ->label('Estado')
                          ->default(true)
                          ->required(),    
                      ])
                      ->createOptionUsing(function (array $data){
                            // Crear el usuario
                            if(empty($data['cu_email'])){
                              $email = generate_email_from_name($data['cu_name']);
                            }else{
                              $email = $data['cu_email'];
                            }

                            $user = User::create([
                                'name' => $data['cu_name'],
                                'email' => $email,
                                'password' => Hash::make($data['cu_num_doc']),
                                'email_verified_at' => now(), 
                            ]);

                            $record = Customer::create([
                              'cu_name' => $data['cu_name'],
                              'cu_num_doc' => $data['cu_num_doc'],
                              'cu_type_doc' => $data['cu_type_doc'],
                              'cu_email' => $data['cu_email'],
                              'cu_address' => $data['cu_address'],
                              'cu_phone' => $data['cu_phone'],
                              'cu_status' => $data['cu_status'],
                              'user_id' => $user->id,
                            ]);
                            
                            return $record;
                      }),

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
                                $set('type', $item->ite_type);
                                $set('sald_price', $item->ite_price);
                                $set('sald_subtotal', $item->ite_price);
                            }
                        })
                        ->searchable()
                        ->preload()
                        ->required(),
  
                  Forms\Components\Hidden::make('type')
                        ->dehydrated(false),
                  Forms\Components\TextInput::make('sald_quantity')
                        ->label('Cantidad')
                        ->integer()
                        ->afterStateUpdated(function ($state, callable $set, callable $get){
                            if($get('item_id')){
                              $item = Item::find($get('item_id'));
                              if($item && $item->ite_stock >= $state){
                                $set('sald_subtotal', 0);
                                $set('sald_subtotal', number_format($get('sald_price') * $state, 2, '.', ''));
                              }else{
                                Notification::make()
                                ->title('Este producto solo tiene un stock de: ' . $item->ite_stock)
                                ->danger()
                                ->send();
                              }
                            }
                        })
                        ->disabled(fn (callable $get) => !$get('item_id'))
                        ->readOnly(fn (callable $get) => $get('type') === 'servicio')
                        ->default(1)
                        ->required(fn (callable $get) => $get('type') !== 'servicio'),
  
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
                Tables\Columns\TextColumn::make('user.name')
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
                  Tables\Actions\EditAction::make()->visible(fn () => auth()->user()->hasRole('super_admin')),
                  Tables\Actions\ViewAction::make(),
                  Tables\Actions\DeleteAction::make()->visible(fn () => auth()->user()->hasRole('super_admin')),
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
            // 'edit' => Pages\EditSale::route('/{record}/edit'),
            // 'pdf' => Pages\PdfViewSale::route('/view/{record}/pdf')
        ];
    }
}
