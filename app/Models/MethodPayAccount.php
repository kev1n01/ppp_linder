<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MethodPayAccount extends Model
{
    protected $fillable = [
      'mpa_name',
      'mpa_cc_numer',
      'mpa_cci_numer',
      'mpa_phone_num',
      'mpa_type',
    ];
}
