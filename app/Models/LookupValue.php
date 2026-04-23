<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LookupValue extends Model
{
    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(LookupType::class, 'lookup_type_id');
    }
}