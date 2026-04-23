<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
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