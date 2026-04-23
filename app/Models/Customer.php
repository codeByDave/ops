<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class);
    }

    public function serviceCalls()
    {
        return $this->hasMany(ServiceCall::class);
    }

    public function customerType()
    {
        return $this->belongsTo(LookupValue::class, 'customer_type_id');
    }

    public function formatPhoneNumber(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);

        if (strlen($digits) !== 10) {
            return $phone;
        }

        return '(' . substr($digits, 0, 3) . ') ' . substr($digits, 3, 3) . '-' . substr($digits, 6, 4);
    }

    public function getFormattedMobilePhoneAttribute(): ?string
    {
        return $this->formatPhoneNumber($this->mobile_phone);
    }

    public function getFormattedHomePhoneAttribute(): ?string
    {
        return $this->formatPhoneNumber($this->home_phone);
    }

    public function getDisplayPhoneAttribute(): ?string
    {
        return $this->formatted_mobile_phone ?: $this->formatted_home_phone;
    }
}