<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
      'cu_name',
      'cu_num_doc',
      'cu_type_doc',
      'cu_email',
      'cu_address',
      'cu_phone',
      'cu_status',
      'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sale()
    {
        return $this->hasMany(Sale::class);
    }
}
