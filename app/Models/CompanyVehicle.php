<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyVehicle extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function booted()
    {
        static::saving(function ($vehicle) {
            $vehicle->plate_number = strtoupper(
                preg_replace('/\s+/', '', trim($vehicle->plate_number ?? ''))
            );
        });
    }
}
