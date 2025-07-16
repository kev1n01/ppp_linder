<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
  protected $fillable = [
    'ser_name',
    'ser_description',
    'ser_price',
    'ser_status'
  ];

  public function saledetail()
  {
      return $this->hasMany(SaleDetail::class);
  }
}
