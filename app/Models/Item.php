<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
      'ite_name',
      'ite_description',
      'ite_price',
      'ite_status',
      'ite_stock',
      'ite_discount',
      'ite_type',
      'ite_image'
    ];

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
