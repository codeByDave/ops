<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }

    public function serviceCalls()
    {
        return $this->hasMany(ServiceCall::class);
    }
}