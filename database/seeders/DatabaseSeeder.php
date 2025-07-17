<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      $this->command->call('shield:generate', [
        '--all' => true,
      ]);

      $this->call([
          ShieldSeeder::class,
          RoleSeeder::class,
          UserSeeder::class,
          SettingSeeder::class,
          ServiceSeeder::class,
      ]);
      
      $this->command->call('shield:super-admin');
    }
}
