<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'property_id',
        'amount',
        'description',
        'status'
    ];

    
    public function property(){
        $this->belongsTo(Property::class);
    }
}
