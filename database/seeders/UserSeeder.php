<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $userAdmin = User::factory()->create([
        'name' => 'Admin',
        'email' => 'admin@tiburon.com',
        'password' => 'admintiburon'
      ]);

      $userEmployee = User::factory()->create([
        'name' => 'Empleado 01',
        'email' => 'empleado1@tiburon.com',
        'password' => 'empleado1'
      ]);

      $userCustomer = User::factory()->create([
        'name' => 'Rosmel Beraun Valdes',
        'email' => 'rosmel@tiburon.com',
        'password' => 'cliente1'
      ]);

      $userAdmin->assignRole('super_admin');
      $userEmployee->assignRole('empleado');
      $userCustomer->assignRole('cliente');

      Employee::create([
        'emp_num_doc' => '71232322',
        'user_id' => $userEmployee->id,
      ]);

      Customer::create([
        'cu_name' => 'Rosmel Beraun Valdes',
        'cu_num_doc' => '52312322',
        'cu_type_doc' => 'dni',
        'user_id' => $userCustomer->id,
      ]);
    }
}
