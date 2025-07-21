<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\EditAction;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Item';
    
    protected static ?string $pluralModelLabel = 'Items';

    protected static ?string $navigationLabel = 'Items';  

    protected static ?string $navigationBadgeTooltip = 'Items';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
      return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(4)
                ->schema([
                  Forms\Components\TextInput::make('ite_name')
                      ->label('Nombre')
                      ->autofocus()
                      ->required(),
                  Forms\Components\TextInput::make('ite_price')
                      ->label('Precio')
                      ->required()
                      ->prefix('S/.')
                      ->numeric(),
                  Forms\Components\Select::make('ite_type')
                      ->label('Tipo')
                      ->options([
                        'producto' => 'Producto',
                        'servicio' => 'Servicio',
                      ])
                      ->required(),
                  Forms\Components\Toggle::make('ite_status')
                      ->label('Estado')
                      ->inline(false)
                      ->default(true)
                      ->required(),
                ]),
                Forms\Components\RichEditor::make('ite_description')
                    ->label('Descripción'),
                Forms\Components\FileUpload::make('ite_image')
                    ->label('Imagen')
                    ->directory('items')
                    ->visibility('private')
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('ite_image')
                ->label('Image')
                ->circular()
                ->defaultImageUrl(url('images/no-image.jpg'))
                ->extraImgAttributes(fn (Item $record): array => [
                  'alt' => "{$record->ite_name} logo",
                  'loading' => 'lazy'
                ]),
                Tables\Columns\TextColumn::make('ite_name')
                ->label('Nombre')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('ite_price')
                    ->label('Precio')
                    ->prefix('S/. ')
                    ->money('PEN')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('ite_status')
                    ->onColor('success')
                    ->label('Estado')
                    ->afterStateUpdated(function () {
                      Notification::make()
                          ->title("Estado actualizado")
                          ->success()
                          ->send();
                    }),
                Tables\Columns\IconColumn::make('ite_type')
                    ->label('Tipo')
                    ->icon(fn (string $state): string => match ($state) {
                      'producto' => 'heroicon-o-bolt',
                      'servicio' => 'heroicon-o-wrench',
                    })
                    ->color(fn (string $state): string => match ($state){
                      'producto' => 'warning',
                      'servicio' => 'secondary',
                    }), 
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
                Tables\Actions\EditAction::make()
                ->before(function (EditAction $action, Item $record) {
                  if($record->ite_image){
                    if (Storage::disk('public')->exists($record->ite_image)) {
                        Storage::disk('public')->delete($record->ite_image);
                    }
                  }
                }),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
