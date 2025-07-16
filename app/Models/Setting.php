<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
      'set_name_business',
      'set_ruc',
      'set_phone',
      'set_province',
      'set_department',
      'set_district',
      'set_ubigeous',
      'set_address',
      'set_logo',
  ];
}
