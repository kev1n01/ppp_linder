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
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-vertical';

    protected static ?string $recordTitleAttribute = 'set_name_business';

    protected static ?string $modelLabel = 'Setting';
    
    protected static ?string $pluralModelLabel = 'Empresas';

    protected static ?string $navigationLabel = 'Configurar empresa';  

    protected static ?string $slug = 'business';
    
    protected static ?string $navigationGroup = 'Configuración';

    protected static ?string $navigationBadgeTooltip = 'Cantidad de usuarios';

    public static function canCreate(): bool
    {
       return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('set_ruc')
                    ->label('Nº RUC')
                    ->maxLength(11)
                    ->minLength(11)
                    ->required()
                    ->suffixAction(
                      fn ($state, $livewire, $set) => Action::make('search')
                          ->icon('heroicon-m-magnifying-glass')
                          ->action(function () use ($state, $livewire, $set) {
                            $livewire->validateOnly('data.set_ruc');
                            if (blank($state)) {
                                Notification::make()
                                    ->title('El N° de ruc no puede estar vacío.')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            $response = Http::withOptions([
                              'verify' => false,
                            ])->get('https://dniruc.apisperu.com/api/v1/ruc/' . $state . '?token=' . env('VITE_TOKEN_DNI_API'))->throw()->json();

                            if (count($response) <= 2) {
                                Notification::make()
                                    ->title($response['message'])
                                    ->warning()
                                    ->send();
                            }

                            if (count($response) > 3) {
                                Notification::make()
                                    ->title('Datos del cliente encontrados.')
                                    ->success()
                                    ->send();
                                $set('set_name_business', $response['razonSocial'] ?? null);
                                $set('set_address', $response['direccion'] ?? null);
                                $set('set_department', $response['departamento'] ?? null);
                                $set('set_province', $response['provincia'] ?? null);
                                $set('set_district', $response['distrito'] ?? null);
                                $set('set_ubigeous', $response['ubigeo'] ?? null);
                            }
                        })
                    ),
                Forms\Components\TextInput::make('set_name_business')
                    ->label('Razón social')
                    ->required(),
                Forms\Components\TextInput::make('set_phone')
                    ->label('Celular')
                    ->maxLength(9)
                    ->minLength(9)
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
                Forms\Components\FileUpload::make('set_logo')
                    ->directory('business')
                    ->visibility('private')
                    ->label('Logo')
                    ->image()
                    ->columnSpan('full')
                  ]);


    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('set_logo')
                  ->label('Logo')
                  ->visibility('private')
                  ->defaultImageUrl(url('https://png.pngtree.com/png-vector/20221125/ourmid/pngtree-no-image-available-icon-flatvector-illustration-thumbnail-graphic-illustration-vector-png-image_40966590.jpg'))
                  ->searchable(),
                Tables\Columns\TextColumn::make('set_name_business')
                  ->label('Razón social')
                  ->searchable(),
                Panel::make([
                  Split::make([
                      Stack::make([
                        Tables\Columns\TextColumn::make('set_ruc')
                            ->label('Nº RUC')
                            ->icon('heroicon-m-identification')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('set_phone')
                            ->icon('heroicon-m-phone')
                            ->label('Celular')
                            ->searchable(),
                      ])->space(3),
                      Stack::make([
                        Tables\Columns\TextColumn::make('set_department')
                            ->icon('heroicon-m-map-pin')
                            ->label('Departamento')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('set_province')
                            ->icon('heroicon-m-map-pin')
                            ->label('Provincia')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('set_district')
                            ->icon('heroicon-m-map-pin')
                            ->label('Distrito')
                            ->searchable(),
                      ]),
                      Stack::make([
                        Tables\Columns\TextColumn::make('set_ubigeous')
                            ->icon('heroicon-m-map-pin')
                            ->label('Ubigeo')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('set_address')
                            ->icon('heroicon-m-map-pin')
                            ->label('Dirección')
                            ->searchable(),
                      ]),
                  ])->from('sm'),
                ])->collapsed(false)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->before(function (EditAction $action, Setting $record) {
                  if($record->set_logo){
                    if (Storage::disk('public')->exists($record->set_logo)) {
                        Storage::disk('public')->delete($record->set_logo);
                    }
                  }
              }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->searchable(false)
            ->paginated(false);
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
            // 'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
