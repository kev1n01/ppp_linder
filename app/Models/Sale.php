<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
      'uuid',
      'customer_id',
      'employee_id',
      'sal_total_amount',
      'sal_payment_method',
      'sal_date'
    ];

    public function scopeForDateRange(Builder $query, $startDate, $endDate): Builder
    {
      return $query->whereBetween('sal_date', [$startDate, $endDate]);
    }
    
    // Para cambiar el campo del binding de un record al hacer Sale $sale 
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
