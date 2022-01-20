<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyReservation extends Model
{
    use HasFactory;

    protected $with = [];

    protected $fillable = [
        'property_id', 
        'tenant_id',
        'isReserved'
    ];

    public function property() {
        return $this->belongsTo(Property::class);
    }

    public function tenant(){
        return $this->belongsTo(Tenant::class);
    }
}
