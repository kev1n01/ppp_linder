<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
      'emp_num_doc',
      'emp_birthdate',
      'emp_email',
      'emp_address',
      'emp_status',
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
