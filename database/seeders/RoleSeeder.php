<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleEmployee = Role::firstOrCreate(['name' => 'empleado']);
        $roleCustomer = Role::firstOrCreate(['name' => 'cliente']);

        $roleEmployee->givePermissionTo([
          'view_any_sale',
          'view_sale',
          'view_any_service',
          'view_service',
        ]);

        $roleCustomer->givePermissionTo([
          'view_any_service',
          'view_service',
        ]);

    }
}
