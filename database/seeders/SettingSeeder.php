<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
          'set_name_business' => 'GRIFO EL TIBURON 555 EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA',
          'set_ruc' => '20573131887',
          'set_phone' => '999999999',
          'set_province' => 'DANIEL ALCIDES CARRION',
          'set_department' => 'PASCO',
          'set_district' => 'YANAHUANCA',
          'set_ubigeous' => '190201',
          'set_address' => 'CAR. CENTRAL KM. 217 CAS. RACRI PASCO DANIEL ALCIDES CARRION YANAHUANCA',
        ]);
    }
}
