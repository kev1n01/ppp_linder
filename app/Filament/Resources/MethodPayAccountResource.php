<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MethodPayAccountResource\Pages;
use App\Filament\Resources\MethodPayAccountResource\RelationManagers;
use App\Models\MethodPayAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MethodPayAccountResource extends Resource
{
    protected static ?string $model = MethodPayAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $recordTitleAttribute = 'mpa_name';

    protected static ?string $modelLabel = 'Cuenta';
    
    protected static ?string $pluralModelLabel = 'Cuentas';

    protected static ?string $navigationLabel = 'Cuentas de pago';  

    protected static ?string $slug = 'methodsAccs';
    
    protected static ?string $navigationGroup = 'Configuración';

    protected static ?string $navigationBadgeTooltip = 'Cuentas de pago';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
      return static::getModel()::count();
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Método de Pago')
                    ->schema([
                        Forms\Components\Select::make('mpa_type')
                            ->options([
                                'digital' => 'Billetera digital',
                                'bancario' => 'Bancario',
                            ])
                            ->label('Tipo de método')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state === 'digital') {
                                    $set('mpa_cc_numer', null);
                                    $set('mpa_cci_numer', null);
                                } elseif ($state === 'bancario') {
                                    $set('mpa_phone_num', null);
                                }
                            }),
    
                        Forms\Components\Select::make('mpa_name')
                            ->searchable()
                            ->options(function (callable $get) {
                                $type = $get('mpa_type');
                                
                                if ($type === 'digital') {
                                    return [
                                        'yape' => 'Yape',
                                        'plin' => 'Plin',
                                        'tunki' => 'Tunki',
                                        'lemon' => 'Lemon',
                                    ];
                                } elseif ($type === 'bancario') {
                                    return [
                                        'bbva' => 'BBVA',
                                        'bcp' => 'BCP',
                                        'interbank' => 'Interbank',
                                        'scotiabank' => 'Scotiabank',
                                        'banco_nacion' => 'Banco de la Nación',
                                    ];
                                }
                                
                                return [];
                            })
                            ->label('Nombre del método')
                            ->required()
                            ->reactive()
                            ->disabled(fn (callable $get) => !$get('mpa_type')),
                    ]),
    
                // Sección para datos bancarios
                Forms\Components\Section::make('Datos Bancarios')
                    ->schema([
                        Forms\Components\TextInput::make('mpa_cc_numer')
                            ->label('Nº de cuenta bancaria')
                            ->required(),
    
                        Forms\Components\TextInput::make('mpa_cci_numer')
                            ->label('Nº de cuenta interbancaria (CCI)')
                            ->maxLength(20)
                            ->minLength(20)
                            ->numeric()
                            ->required()
                            ->helperText('La CCI debe tener exactamente 20 dígitos'),
                    ])
                    ->visible(fn (callable $get) => $get('mpa_type') === 'bancario')
                    ->collapsed(false),
    
                // Sección para billetera digital
                Forms\Components\Section::make('Billetera Digital')
                    ->schema([
                        Forms\Components\TextInput::make('mpa_phone_num')
                            ->maxLength(9)
                            ->minLength(9)
                            ->label('Celular de billetera digital')
                            ->tel()
                            ->numeric()
                            ->required()
                            ->placeholder('987654321')
                            ->helperText('Ingrese el número celular asociado a la billetera digital'),
                    ])
                    ->visible(fn (callable $get) => $get('mpa_type') === 'digital')
                    ->collapsed(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mpa_name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mpa_cc_numer')
                    ->label('Nº CC')
                    ->placeholder('N/A')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mpa_cci_numer')
                    ->label('Nº CCI')
                    ->placeholder('N/A')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mpa_phone_num')
                    ->label('Nº celular')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mpa_type')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tipo')
                    ->searchable(),
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListMethodPayAccounts::route('/'),
            // 'create' => Pages\CreateMethodPayAccount::route('/create'),
            // 'edit' => Pages\EditMethodPayAccount::route('/{record}/edit'),
        ];
    }
}
