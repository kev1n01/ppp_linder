<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
      'customer_id',
      'employee_id',
      'sal_total_amount',
      'sal_payment_method',
      'sal_date'
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function sale_details()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
