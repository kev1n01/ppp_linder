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
        // Tomamos el nombre desde temp_name antes de eliminarlo
        $nombre = $data['temp_name'];

        
        // Solo crear usuario si no existe un user_id y se proporcionó un email
        if (empty($data['user_id'])) {
            // Crear el usuario
            if(empty($data['emp_email'])){
              $email = generate_email_from_name($nombre);
            }else{
              $email = $data['emp_email'];
            }

            $user = User::create([
                'name' => $nombre,
                'email' => $email,
                'password' => Hash::make($data['emp_num_doc']),
                'email_verified_at' => now(), 
            ]);

            // Asignar el rol cliente
            $user->assignRole('empleado');

            // Asignar el user_id al customer
            $data['user_id'] = $user->id;
        }

        // Eliminamos temp_name del array para que no intente insertarlo en employees
        unset($data['temp_name']);

        return $data;
    }
}