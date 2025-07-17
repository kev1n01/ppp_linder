<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $modelLabel = 'Empleado';
    
    protected static ?string $pluralModelLabel = 'Empleados';

    protected static ?string $navigationLabel = 'Empleados';  

    protected static ?string $navigationBadgeTooltip = 'Empleados';

    public static function getNavigationBadge(): ?string
    {
      return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('emp_num_doc')
                    ->label('Nº DNI')
                    ->unique(ignoreRecord: true)
                    ->autofocus()
                    ->required()
                    ->maxLength(8)
                    ->minLength(8)
                    ->required()
                    ->suffixAction(
                      fn ($state, $livewire, $set) => Action::make('search')
                          ->icon('heroicon-m-magnifying-glass')
                          ->action(function () use ($state, $livewire, $set) {
                              $livewire->validateOnly('data.emp_num_doc');
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
                                      $set('temp_name', $response['nombres'] . ' ' . $response['apellidoPaterno'] . ' ' . $response['apellidoMaterno'] ?? null);
                                  }
                              }
                          })
                  ),
                Forms\Components\TextInput::make('temp_name')
                    ->label('Nombres')
                    ->required()
                    ->disabled()
                    ->dehydrated(true),
                Forms\Components\DatePicker::make('emp_birthdate')
                    ->label('Fecha nacimiento'),
                Forms\Components\TextInput::make('emp_email')
                    ->label('Correo electrónico')
                    ->email(),
                Forms\Components\TextInput::make('emp_address')
                    ->label('Dirección'),
                Forms\Components\Toggle::make('emp_status')
                    ->label('Estado')
                    ->default(true)
                    ->required(),
                Forms\Components\Hidden::make('user_id'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nombres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('emp_num_doc')
                    ->label('Nº DNI')
                    ->searchable(),
                Tables\Columns\TextColumn::make('emp_birthdate')
                    ->label('Fecha nacimiento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('emp_email')
                    ->label('Correo electrónico')
                    ->searchable(),
                Tables\Columns\TextColumn::make('emp_address')
                    ->label('Dirección')
                    ->searchable(),
                Tables\Columns\IconColumn::make('emp_status')
                    ->label('Estado')
                    ->boolean(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
