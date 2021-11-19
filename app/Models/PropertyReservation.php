<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id', 
        'tenant_id',
        'isReserved'
    ];

    function property() {
        return $this->belongsTo(Property::class);
    }
}
