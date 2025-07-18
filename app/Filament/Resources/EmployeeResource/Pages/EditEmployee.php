<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = User::find($data['user_id']);
        if($user->name !== $data['temp_name']){
          $email = generate_email_from_name($data['temp_name']);
        }
        $user->email = $email;
        $user->name = $data['temp_name'];
        $user->password = Hash::make($data['emp_num_doc']);
        $user->save();
        unset($data['temp_name']);
        return $data;
    }
}
