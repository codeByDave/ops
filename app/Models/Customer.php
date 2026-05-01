<?php

namespace App\Models;

use App\Helpers\PhoneHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($customer) {
            if (empty($customer->public_id)) {
                $customer->public_id = self::generatePublicId();
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function serviceCalls()
    {
        return $this->hasMany(ServiceCall::class)
            ->orderByDesc('created_at');
    }

    public function customerType()
    {
        return $this->belongsTo(LookupValue::class, 'customer_type_id');
    }

    public function getFormattedMobilePhoneAttribute(): ?string
    {
        return PhoneHelper::format($this->mobile_phone);
    }

    public function getDisplayPhoneAttribute(): ?string
    {
        return $this->formatted_mobile_phone;
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function participantServiceCalls()
    {
        return $this->belongsToMany(
            ServiceCall::class,
            'service_call_participants',
            'customer_id',
            'service_call_id'
        )->withPivot('role')
            ->withTimestamps();
    }

    private static function generatePublicId(): string
    {
        do {
            $id = 'CUST-' . str_pad((string) mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('public_id', $id)->exists());

        return $id;
    }
}
