<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $items = [
        // productos
        [
            'ite_name' => 'Gasolina 90',
            'ite_description' => 'Combustible gasolina de 90 octanos',
            'ite_price' => 18.50,
            'ite_status' => true,
            'ite_stock' => '10',
            'ite_discount' => '2',
            'ite_type' => 'producto',
        ],
        [
            'ite_name' => 'Gasolina 95',
            'ite_description' => 'Combustible gasolina de 95 octanos',
            'ite_price' => 19.80,
            'ite_status' => true,
            'ite_stock' => '20',
            'ite_discount' => '5',
            'ite_type' => 'producto',
        ],
        [
            'ite_name' => 'Gasolina 97',
            'ite_description' => 'Combustible gasolina de 97 octanos',
            'ite_price' => 20.50,
            'ite_status' => true,
            'ite_stock' => '15',
            'ite_discount' => '7',
            'ite_type' => 'producto',
        ],
        [
            'ite_name' => 'Diesel',
            'ite_description' => 'Combustible diésel B5 S50',
            'ite_price' => 17.90,
            'ite_status' => true,
            'ite_stock' => '52',
            'ite_discount' => '3',
            'ite_type' => 'producto',
        ],
        [
            'ite_name' => 'Gas GLP',
            'ite_description' => 'Gas Licuado de Petróleo para vehículos',
            'ite_price' => 8.50,
            'ite_status' => true,
            'ite_stock' => '22',
            'ite_discount' => '12',
            'ite_type' => 'producto',
        ],

        // servicios adicionales
        [
            'ite_name' => 'Lavado de auto',
            'ite_description' => 'Lavado completo del vehículo',
            'ite_price' => 15.00,
            'ite_status' => true,
            'ite_stock' => '1',
            'ite_discount' => '5',
            'ite_type' => 'servicio',
        ],
        [
            'ite_name' => 'Cambio de aceite',
            'ite_description' => 'Cambio de aceite de motor con mano de obra incluida',
            'ite_price' => 50.00,
            'ite_status' => true,
            'ite_stock' => '1',
            'ite_discount' => '11',
            'ite_type' => 'servicio',
        ],
        [
            'ite_name' => 'Revisión de frenos',
            'ite_description' => 'Servicio de inspección y ajuste de frenos',
            'ite_price' => 30.00,
            'ite_status' => true,
            'ite_stock' => '1',
            'ite_discount' => '5',
            'ite_type' => 'servicio',
        ],
        [
            'ite_name' => 'Inflado de llantas',
            'ite_description' => 'Inflado de llantas con aire o nitrógeno',
            'ite_price' => 5.00,
            'ite_status' => true,
            'ite_stock' => '1',
            'ite_discount' => '8',
            'ite_type' => 'servicio',
        ],
        [
            'ite_name' => 'Balanceo de llantas',
            'ite_description' => 'Servicio de balanceo de neumáticos',
            'ite_price' => 25.00,
            'ite_status' => true,
            'ite_stock' => '1',
            'ite_discount' => '7',
            'ite_type' => 'servicio',
        ],
      ];

      foreach ($items as $item) {
          Item::create($item);
      }
    }
}
