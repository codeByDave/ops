<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LookupType extends Model
{
    protected $guarded = [];

    public function values()
    {
        return $this->hasMany(LookupValue::class);
    }
}