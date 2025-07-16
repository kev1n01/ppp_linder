<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'cu_num_doc';

    protected static ?string $modelLabel = 'Cliente';
    
    protected static ?string $pluralModelLabel = 'Clientes';

    protected static ?string $navigationLabel = 'Clientes';  

    protected static ?string $navigationBadgeTooltip = 'Clientes';

    public static function getNavigationBadge(): ?string
    {
      return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cu_num_doc')
                    ->label('Nº de documento')
                    ->unique(ignoreRecord: true)
                    ->autofocus()
                    ->required()
                    ->maxLength(11)
                    ->minLength(8)
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cu_name')
                    ->label('Nombres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cu_num_doc')
                    ->label('Nº de documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cu_email')
                    ->default('N/A')
                    ->label('Correo Electrónico')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('cu_type_doc')
                    ->label('Tipo de documento')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('cu_address')
                    ->default('N/A')
                    ->label('Dirección')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('cu_phone')
                    ->default('N/A')
                    ->label('Celular')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('cu_status')
                    ->label('Estado'),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            // 'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
