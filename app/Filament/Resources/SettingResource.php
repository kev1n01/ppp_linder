<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-vertical';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Setting';
    
    protected static ?string $pluralModelLabel = 'Empresas';

    protected static ?string $navigationLabel = 'Configurar empresa';  

    protected static ?string $slug = 'business';
    
    protected static ?string $navigationGroup = 'Configuración';

    protected static ?string $navigationBadgeTooltip = 'Cantidad de usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('set_name_business')
                    ->label('Razón social')
                    ->required(),
                Forms\Components\TextInput::make('set_ruc')
                    ->label('Nº RUC')
                    ->required(),
                Forms\Components\TextInput::make('set_phone')
                    ->label('Celular')
                    ->tel()
                    ->required(),
                Forms\Components\TextInput::make('set_department')
                    ->label('Departamento')
                    ->required(),
                Forms\Components\TextInput::make('set_province')
                    ->label('Provincia')
                    ->required(),
                Forms\Components\TextInput::make('set_district')
                    ->label('Distrito')
                    ->required(),
                Forms\Components\TextInput::make('set_ubigeous')
                    ->label('Ubigeo')
                    ->required(),
                Forms\Components\TextInput::make('set_address')
                    ->label('Dirección')
                    ->required(),
                Forms\Components\TextInput::make('set_logo')
                    ->label('Logo'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('set_name_business')
                    ->label('Razón social')
                    ->searchable(),
                Tables\Columns\TextColumn::make('set_ruc')
                    ->label('Nº RUC')
                    ->searchable(),
                Tables\Columns\TextColumn::make('set_phone')
                    ->label('Celular')
                    ->searchable(),
                Tables\Columns\TextColumn::make('set_province')
                    ->label('Provincia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('set_department')
                    ->label('Departamento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('set_district')
                    ->label('Distrito')
                    ->searchable(),
                Tables\Columns\TextColumn::make('set_ubigeous')
                    ->label('Ubigeo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('set_address')
                    ->label('Dirección')
                    ->searchable(),
                Tables\Columns\TextColumn::make('set_logo')
                    ->label('Logo')
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
            'index' => Pages\ListSettings::route('/'),
            // 'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
