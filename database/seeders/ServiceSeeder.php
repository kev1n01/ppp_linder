<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $services = [
        // Combustibles
        [
            'ser_name' => 'Gasolina 90',
            'ser_description' => 'Combustible gasolina de 90 octanos',
            'ser_price' => 18.50,
            'ser_status' => true,
        ],
        [
            'ser_name' => 'Gasolina 95',
            'ser_description' => 'Combustible gasolina de 95 octanos',
            'ser_price' => 19.80,
            'ser_status' => true,
        ],
        [
            'ser_name' => 'Gasolina 97',
            'ser_description' => 'Combustible gasolina de 97 octanos',
            'ser_price' => 20.50,
            'ser_status' => true,
        ],
        [
            'ser_name' => 'Diesel',
            'ser_description' => 'Combustible diésel B5 S50',
            'ser_price' => 17.90,
            'ser_status' => true,
        ],
        [
            'ser_name' => 'Gas GLP',
            'ser_description' => 'Gas Licuado de Petróleo para vehículos',
            'ser_price' => 8.50,
            'ser_status' => true,
        ],

        // Servicios adicionales
        [
            'ser_name' => 'Lavado de auto',
            'ser_description' => 'Lavado completo del vehículo',
            'ser_price' => 15.00,
            'ser_status' => true,
        ],
        [
            'ser_name' => 'Cambio de aceite',
            'ser_description' => 'Cambio de aceite de motor con mano de obra incluida',
            'ser_price' => 50.00,
            'ser_status' => true,
        ],
        [
            'ser_name' => 'Revisión de frenos',
            'ser_description' => 'Servicio de inspección y ajuste de frenos',
            'ser_price' => 30.00,
            'ser_status' => true,
        ],
        [
            'ser_name' => 'Inflado de llantas',
            'ser_description' => 'Inflado de llantas con aire o nitrógeno',
            'ser_price' => 5.00,
            'ser_status' => true,
        ],
        [
            'ser_name' => 'Balanceo de llantas',
            'ser_description' => 'Servicio de balanceo de neumáticos',
            'ser_price' => 25.00,
            'ser_status' => true,
        ],
      ];

      foreach ($services as $service) {
          Service::create($service);
      }
    }
}
