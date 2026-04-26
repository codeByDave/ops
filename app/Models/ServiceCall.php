<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCall extends Model
{
    protected $guarded = [];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'dispatched_at' => 'datetime',
        'enroute_at' => 'datetime',
        'arrived_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function companyVehicle()
    {
        return $this->belongsTo(CompanyVehicle::class, 'assigned_company_vehicle_id');
    }

    public function status()
    {
        return $this->belongsTo(LookupValue::class, 'status_id');
    }

    public function serviceType()
    {
        return $this->belongsTo(LookupValue::class, 'service_type_id');
    }
}