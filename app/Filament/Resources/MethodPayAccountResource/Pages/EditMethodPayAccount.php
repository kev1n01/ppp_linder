<?php

namespace App\Filament\Resources\MethodPayAccountResource\Pages;

use App\Filament\Resources\MethodPayAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMethodPayAccount extends EditRecord
{
    protected static string $resource = MethodPayAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
