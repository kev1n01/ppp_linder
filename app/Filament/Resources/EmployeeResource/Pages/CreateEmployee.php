<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
    
    /**
     * Método para crear el usuario antes de crear el cliente
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Solo crear usuario si no existe un user_id y se proporcionó un email
        if (empty($data['user_id'])) {
            // Crear el usuario
            if(empty($data['emp_email'])){
              $email = generate_email_from_name($data['emp_name']);
            }else{
              $email = $data['emp_email'];
            }

            $user = User::create([
                'name' => $data['emp_name'] ?? 'Empleado', // Por si no se recibe
                'email' => $email,
                'password' => Hash::make($data['emp_num_doc']),
                'email_verified_at' => now(), 
            ]);

            // Asignar el rol cliente
            $user->assignRole('empleado');

            // Asignar el user_id al customer
            $data['user_id'] = $user->id;
        }

        return $data;
    }
}
