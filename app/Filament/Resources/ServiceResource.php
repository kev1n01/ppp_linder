<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $modelLabel = 'Servicio';
    
    protected static ?string $pluralModelLabel = 'Servicios';

    protected static ?string $navigationLabel = 'Servicios';  

    protected static ?string $navigationBadgeTooltip = 'Servicios';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
      return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                  ->schema([
                    Forms\Components\TextInput::make('ser_name')
                        ->label('Nombre de servicio')
                        ->autofocus()
                        ->required(),
                    Forms\Components\TextInput::make('ser_price')
                        ->label('Precio')
                        ->required()
                        ->prefix('S/.')
                        ->numeric(),
                    Forms\Components\Toggle::make('ser_status')
                        ->label('Estado')
                        ->inline(false)
                        ->default(true)
                        ->required(),
                  ]),
                Forms\Components\RichEditor::make('ser_description')
                    ->label('Descripción')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ser_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ser_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('ser_status')
                    ->boolean(),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
