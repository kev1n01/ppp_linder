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
          'set_name_business' => 'MECANICA AUTOMOTRIZ FLOPACH',
          'set_ruc' => '10763703458',
          'set_phone' => '933865935',
          'set_province' => 'HUANUCO',
          'set_department' => 'HUANUCO',
          'set_district' => 'HUANUCO',
          'set_ubigeous' => '302941',
          'set_address' => 'LAS LOMAS MA NA',
        ]);
    }
}
