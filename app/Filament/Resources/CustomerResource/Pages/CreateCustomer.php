<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

     /**
     * Método para crear el usuario antes de crear el cliente
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Solo crear usuario si no existe un user_id y se proporcionó un email
        if (empty($data['user_id'])) {
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

            // Asignar el rol cliente
            $user->assignRole('cliente');

            // Asignar el user_id al customer
            $data['user_id'] = $user->id;
        }

        return $data;
    }
}
